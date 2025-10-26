@extends('layouts.app')

@section('content')
    <style>
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 80px;
            overflow-y: auto;
        }

        .modal-content {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            width: 500px;
            max-height: 80vh;
            /* ✅ هذا يحدد أقصى ارتفاع للمودال */
            overflow-y: auto;
            /* ✅ وهذا يتيح التمرير داخله */
        }
    </style>



    <div class="container mt-4">
        <h4>{{ __('campaigns.advertising_campaign') }}</h4>

        <select name="site" id="site" class="form-control">
            <option value="">{{ __('campaigns.select_site') }}</option>
            <option value="aldhahran">{{ __('campaigns.aldhahran') }}</option>
            <option value="albashaer">{{ __('campaigns.albashaer') }}</option>
            <option value="jeddah">{{ __('components.jeddah') }}</option>
            <option value="alfursan">{{ __('components.alfursan') }}</option>
        </select>

        <div id="overlay" class="overlay d-none">
            <div class="modal-content">
                <button type="button" class="btn btn-secondary mb-3" id="changeSiteBtn">
                    {{ __('campaigns.change_site') }}
                </button>


                <ul class="nav nav-tabs mb-3" id="campaignTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="existing-tab" data-toggle="tab" href="#existingCampaign"
                            role="tab">
                            {{ __('campaigns.existing_campaign') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="new-tab" data-toggle="tab" href="#newCampaign" role="tab">
                            {{ __('campaigns.new_campaign') }}
                        </a>
                    </li>
                </ul>

                <div class="tab-content">

                    <div class="tab-pane fade show active" id="existingCampaign" role="tabpanel">
                        <form method="GET" action="{{ route('admin.campaign.show') }}">
                            <div class="form-group">
                                <label>{{ __('campaigns.select_campaign') }}</label>
                                <select name="id" id="campaignSelect" class="form-control" required>
                                    <option value="">{{ __('campaigns.select_campaign') }}</option>
                                    @foreach($campaigns as $campaign)
                                        <option value="{{ $campaign->id }}" data-site="{{ $campaign->site }}">
                                            {{ $campaign->name }} ({{ $campaign->from_date }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <button type="submit" class="btn btn-info mt-2">{{ __('campaigns.search_campaign') }}</button>
                        </form>
                    </div>


                    <div class="tab-pane fade" id="newCampaign" role="tabpanel">
                       <form method="POST" action="{{ route('admin.campaign.result') }}">
    @csrf
                            <input type="hidden" name="site" id="siteInput">
                            <div class="form-group">
                                <label>{{ __('campaigns.campaign_name') }}</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>{{ __('campaigns.from_date') }}</label>
                                <input type="date" name="from_date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>{{ __('campaigns.end_date') }}</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>{{ __('campaigns.select_source') }}</label>
                                <select name="source" id="sourceSelect" class="form-control" required></select>
                            </div>
                            <input type="hidden" name="source_name" id="sourceNameInput">
                            <div class="form-group">
                                <label>{{ __('campaigns.total_amount') }}</label>
                                <input type="number" name="total_amount" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>{{ __('campaigns.impression') }}</label>
                                <input type="number" name="impression" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>{{ __('campaigns.clicks') }}</label>
                                <input type="number" name="clicks" class="form-control">
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">{{ __('campaigns.submit') }}</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        const siteSelect = $('#site');
        const overlay = $('#overlay');
        const sourceSelect = $('#sourceSelect');
        const siteInput = $('#siteInput');

        $(function() {
            $('#campaignTabs a').on('click', function(e) {
                e.preventDefault();
                $(this).tab('show');
            });
        });

        siteSelect.on('change', function() {
            const site = $(this).val();
            if (!site) return;
            $('#campaignSelect option').each(function () {
    const optionSite = $(this).data('site');

    if (!optionSite || optionSite === site) {
        $(this).show();
    } else {
        $(this).hide();
    }
});


            $.ajax({
                url: "{{ route('admin.campaign.sources') }}",
                type: 'GET',
                data: {
                    site
                },
                success: function(data) {
                    if (data.status !== 'success' || !Array.isArray(data.sources)) {
                        alert('البيانات غير صحيحة من السيرفر.');
                        return;
                    }
    sourceSelect.empty().append(
                        '<option value="">{{ __('campaigns.select_source') }}</option>'
                    );
                    data.sources.forEach(source => {
                        sourceSelect.append(
                            `<option value="${source.id}">${source.name}</option>`);
                    });

                   sourceSelect.on('change', function() {
                        const selectedOption = $(this).find('option:selected');
                        $('#sourceNameInput').val(selectedOption.text());
                    });

                    siteInput.val(site);
                    $('#existingSiteInput').val(site);

                    overlay.removeClass('d-none');
                    setTimeout(() => {
                        overlay.addClass('show');
                        $('html, body').animate({
                            scrollTop: overlay.offset().top
                        }, 'slow');
                    }, 10);
                },
                error: function(xhr) {
                    alert('فشل في تحميل المصادر من السيرفر.');
                    console.error(xhr.responseText);
                }
            });
        });


        $('#changeSiteBtn').on('click', function() {
            overlay.removeClass('show');
            setTimeout(() => {
                overlay.addClass('d-none');
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
            }, 300);
            siteSelect.val('');
        });
    </script>
@endsection
