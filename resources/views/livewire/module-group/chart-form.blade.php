<div>
    <div class="main p-5 bg-white m-0">
        <div class="menu d-flex justify-content-between ">
            <div class="d-flex gap-2">
                <select class="custom-select" name="plot_filter" id='plot_filter'
                    wire:change="changeCategoryEvent($event.target.value)">
                    <option value="all" {{ $category == "all" ? 'selected' : '' }}>All Items</option>
                    @foreach($items as $item)
                    <option value="{{ $item->id }}" {{ $category == $item->id ? 'selected' : '' }}>{{ $item->name }}
                    </option>
                    @endforeach
                </select>

                <select class="custom-select" name="type_filter" id="type_filter"
                    wire:change="changeTypeEvent($event.target.value)">
                    @foreach($options as $key => $option)
                    <option value="{{ $key }}" {{$type == $key ? "selected" : "" }}>{{ $option }}</option>
                    @endforeach
                </select>

                @if (isset($type) && $type!=='year')
                <select class="custom-select" name="year_filter" id="year_filter"
                    wire:change="changeYearEvent($event.target.value)">
                    @foreach($years as $item)
                    <option value="{{$item->resultyear}}" {{$item->resultyear == $year ? "selected" : "" }}>
                        {{$item->resultyear}}</option>
                    @endforeach
                </select>
                @endif

                @if (isset($type) && $type!=='year' && $type!=='month')
                <select class="custom-select" name="month_filter" id="month_filter"
                    wire:change="changeMonthEvent($event.target.value)">
                    @for ($i = 1; $i <= 12; $i++) <option value="{{$i}}" {{$month==$i?"selected":""}}>{{$i}}</option>
                        @endfor
                </select>
                @endif

                @if (isset($type) && $type==='day')
                <select class="custom-select" name="week_filter" id="week_filter"
                    wire:change="changeWeekEvent($event.target.value)">
                    @for ($i = 1; $i <= 5; $i++) <option value="{{$i}}" {{$week==$i?"selected":""}}>{{$i}}</option>
                        @endfor
                </select>
                @endif

            </div>
            <div class="d-flex justify-content-between align-items-center gap-2">
                <div>
                    <input type="color" style="height:40px; width: 100px; vertical-align:middle" id="color"
                        value="#04364A" class="color" onchange="colorhandle()">
                </div>
                <select class="custom-select" id="charttype" onchange="typehandle()">
                    <option value="bar">bar</option>
                    <option value="pie">pie</option>
                    <option value="line">line</option>
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white p-5">
        <canvas id="PlotChart" wire:key="PlotChart" style="width: 100%; max-height: 300px;"></canvas>
    </div>

    <div class="p-5">
        <table id="example" class="table table-striped table-hover table-bordered text-center w-100">
            <thead>
                <tr>
                    <th>Dates</th>
                    <th>QTY sold</th>
                    <th>QTY remaining</th>
                    <th>Item name</th>
                </tr>
            </thead>
            <tbody>
                @if($plots != null)
                @foreach ($plots as $plot)
                <tr>
                    <td>{{ $plot->dates ?? "" }}</td>
                    <td>{{ $plot->qty_sold ?? "" }}</td>
                    <td>{{ $plot->qty_remain ?? "" }}</td>
                    <td>{{ $plot->item->name ?? "" }}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="4">There is no data</td>
                </tr>
                @endif
            <tbody>
        </table>
    </div>
    @push('scripts')
    <script>
    window.addEventListener('DOMContentLoaded', (event) => {
        let chart = null;
        renderChart(@json($data));
    });

    Livewire.on('renderChart', result => {
        if (chart) {
            chart.destroy();
        }
        renderChart(result[0]['data']);
    })

    const renderChart = chartData => {
        const xValues = chartData.map(item => item.resultx);
        const yValues = chartData.map(item => item.resulty);
        var PlotChart = document.getElementById('PlotChart');
        chart = new Chart(PlotChart, {
            type: "pie",
            data: {
                labels: xValues,
                datasets: [{
                    label: '# of Votes',
                    data: yValues,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        chart.config.type = 'bar';
        chart.update();

        $(function() {
            new DataTable('#example');
        })
    }

    function typehandle() {
        type = document.getElementById("charttype").value;
        chart.config.type = type;
        chart.update();
    }

    function colorhandle() {
        color = document.getElementById("color").value;
        chart.data.datasets.forEach((dataset) => {
            dataset.borderColor = color;
        });
        chart.update();
    }
    </script>
    @endpush

</div>