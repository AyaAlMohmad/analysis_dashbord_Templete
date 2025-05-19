<div
        style="background-color: #f9f6f2; padding: 60px 20px; font-family: 'Arial', sans-serif; direction: rtl; position: relative;">

        <!-- Logo Top -->
        <div style="position: absolute; top: 30px; right: 30px;">
            <img src="{{ asset('build/logo.png') }}" alt="Tatwir Logo" style="height: 50px;">
        </div>

        <!-- Title -->
        <div style="text-align: center; margin-bottom: 40px;">
            <h2 style="font-size: 28px; color: #8b5a3b; border-bottom: 2px solid #8b5a3b; display: inline-block;">
                الزيارات والسداد والعقود شهر مارس
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



                <canvas id="visitsChart" style="max-width: 100%; height: 250px;"></canvas>

                <table style="width: 100%; border-collapse: collapse; margin-top: 30px;">
                    <thead style="background-color: #ffe082; font-weight: bold;">
                        <tr>
                            <th>الفترة</th>
                            <th>سنتي سكويب</th>
                            <th>يناير</th>
                            <th>فبراير</th>
                            <th>الأسبوع الأول</th>
                            <th>الأسبوع الثاني</th>
                            <th>الأسبوع الثالث</th>
                            <th>الأسبوع الرابع</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background-color: #f4f4f4;">
                            <td>الزيارات</td>
                            <td>56</td>
                            <td>161</td>
                            <td>159</td>
                            <td>75</td>
                            <td>42</td>
                            <td>48</td>
                            <td>24</td>
                        </tr>
                        <tr style="background-color: #f9f9f9;">
                            <td>السداد</td>
                            <td>146</td>
                            <td>56</td>
                            <td>43</td>
                            <td>6</td>
                            <td>18</td>
                            <td>15</td>
                            <td>4</td>
                        </tr>
                        <tr style="background-color: #f4f4f4;">
                            <td>العقود</td>
                            <td>56</td>
                            <td>48</td>
                            <td>36</td>
                            <td>10</td>
                            <td>6</td>
                            <td>11</td>
                            <td>4</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- ✅ Script for Chart.js -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const visitsChartCanvas = document.getElementById('visitsChart').getContext('2d');
                new Chart(visitsChartCanvas, {
                    type: 'line',
                    data: {
                        labels: ['سنتي سكويب', 'يناير', 'فبراير', 'الأسبوع الأول', 'الأسبوع الثاني', 'الأسبوع الثالث',
                            'الأسبوع الرابع'
                        ],
                        datasets: [{
                                label: 'الزيارات',
                                data: [56, 161, 159, 75, 42, 48, 24],
                                borderColor: '#1f4e79',
                                backgroundColor: '#1f4e79',
                                tension: 0.4
                            },
                            {
                                label: 'السداد',
                                data: [146, 56, 43, 6, 18, 15, 4],
                                borderColor: '#db4437',
                                backgroundColor: '#db4437',
                                tension: 0.4
                            },
                            {
                                label: 'العقود',
                                data: [56, 48, 36, 10, 6, 11, 4],
                                borderColor: '#0f9d58',
                                backgroundColor: '#0f9d58',
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 20
                                }
                            }
                        }
                    }
                });
            </script>



        </div>


        <!-- Logo Bottom -->
        <div style="position: absolute; right: 30px; bottom: 30px;">
            <img src="{{ asset('images/logo1.png') }}" alt="Azyan Logo" style="height: 70px;">
        </div>

    </div>