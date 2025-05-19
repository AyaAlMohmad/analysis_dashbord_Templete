<div
style="background-color: #f9f6f2; padding: 60px 20px; font-family: 'Arial', sans-serif; direction: rtl; position: relative;">

<!-- Logo Top -->
<div style="position: absolute; top: 30px; right: 30px;">
    <img src="{{ asset('build/logo.png') }}" alt="Tatwir Logo" style="height: 50px;">
</div>

<!-- Title -->
<div style="text-align: center; margin-bottom: 40px;">
    <h2 style="font-size: 28px; color: #8b5a3b; border-bottom: 2px solid #8b5a3b; display: inline-block;">
        مستهدف شهر مارس

    </h2>
</div>


<!-- Content Row -->
<div style="display: flex; flex-direction: row-reverse; align-items: flex-start;">
    <!-- Side Decoration -->
    <div style="flex: 0 0 auto; min-width: 100px; position: absolute; top: 100px;">
        <img src="{{ asset('images/style2.png') }}" alt="Decoration" style="height: 500px;">
    </div>

    <!-- Main Tables Container -->



    <div
        style="max-width: 95%; margin: 40px auto; font-family: 'Arial', sans-serif; font-size: 13px; text-align: center;">




        <div style="max-width: 400px; margin: 0 auto;">
            <canvas id="targetChart" height="200"></canvas>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-top: 20px; background-color: white;">


            <thead style="background-color: #ffe082; font-weight: bold;">
                <tr>
                    <th style="border: 1px solid #ccc;">الحالة</th>
                    <th style="border: 1px solid #ccc;">المستهدف</th>
                    <th style="border: 1px solid #ccc;">محقق</th>
                    <th style="border: 1px solid #ccc;">النسبة</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid #ccc;">مُهتم</td>
                    <td style="border: 1px solid #ccc;">6155</td>
                    <td style="border: 1px solid #ccc;">481</td>
                    <td style="border: 1px solid #ccc;">7.81%</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ccc;">موعد</td>
                    <td style="border: 1px solid #ccc;">1231</td>
                    <td style="border: 1px solid #ccc;">204</td>
                    <td style="border: 1px solid #ccc;">16.57%</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ccc;">زيارة</td>
                    <td style="border: 1px solid #ccc;">360</td>
                    <td style="border: 1px solid #ccc;">60</td>
                    <td style="border: 1px solid #ccc;">16.67%</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ccc;">سداد حجز</td>
                    <td style="border: 1px solid #ccc;">80</td>
                    <td style="border: 1px solid #ccc;">16</td>
                    <td style="border: 1px solid #ccc;">20.00%</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ccc;">إلغاء</td>
                    <td style="border: 1px solid #ccc;">7</td>
                    <td style="border: 1px solid #ccc;">2</td>
                    <td style="border: 1px solid #ccc;">—</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ccc;">عقد</td>
                    <td style="border: 1px solid #ccc;">48</td>
                    <td style="border: 1px solid #ccc;">14</td>
                    <td style="border: 1px solid #ccc;">29.17%</td>
                </tr>
            </tbody>
        </table>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctxTargetChart = document.getElementById('targetChart').getContext('2d');
        new Chart(ctxTargetChart, {
            type: 'bar',
            data: {
                labels: ['مُهتم', 'موعد', 'زيارة', 'سداد حجز', 'إلغاء', 'عقد'],
                datasets: [{
                        label: 'المستهدف',
                        data: [6155, 1231, 360, 80, 7, 48],
                        backgroundColor: '#3a3a3a'
                    },
                    {
                        label: 'محقق',
                        data: [481, 204, 60, 16, 2, 14],
                        backgroundColor: '#c87c2a'
                    },
                    {
                        label: 'النسبة',
                        data: [7.81, 16.57, 16.67, 20.00, 0, 29.17],
                        backgroundColor: '#0f683f'
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1000
                        }
                    }
                }
            }
        });
    </script>





    <!-- Logo Bottom -->
    <div style="position: absolute; right: 30px; bottom: 30px;">
        <img src="{{ asset('images/logo1.png') }}" alt="Azyan Logo" style="height: 70px;">
    </div>

</div>
</div>