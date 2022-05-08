<div class="box_general padding_bottom">

	<div class="header_box version_2">
		<h2><i class="fa fa-play-circle"></i>Продажи моих курсов</h2>
	</div>

    <div class="row">

                    
        @if (count($coursesStat) > 0)

        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Курс</th>
                    <th>Цена</th>
                    <th>Дата</th>
                    <th>Скидка</th>
                    <th>Оплачен</th>
       
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Курс</th>
                    <th>Цена</th>
                    <th>Дата</th>
                    <th>Скидка</th>
                    <th>Оплачен</th>
    
                </tr>
                </tfoot>
                <tbody>    
                @foreach ($coursesStat as $data)
                    <tr>
                        <td><a href="{{ route('courseShow', ['slug'=>$data->slug]) }}" target="_blank">{{ $data->title }}</a></td>
                        <td>{{ $data->price }}</td>
                        <td>{{ $data->updated_at }}</td>
                        <td>{{ $data->coupon_percent }}%</td>
                        <td>
                        @if ( $data->no_pay == 0 )
                            +
                        @else
                            -
                        @endif
                        </td>
                        <td>{{ $data->updated_at }}</td>
              
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
        </div>

        @else
            Продаж нет.
        @endif

    </div>

</div>
