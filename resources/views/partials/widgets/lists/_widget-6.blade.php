<?php
    use App\Http\Controllers\SampleDataController;

    $listRows = array(
        array(
            'color' => 'warning',
            'icon' => 'icons/duotune/abstract/abs027.svg',
            'title' => 'Group lunch celebration',
            'text' => 'Due in 2 Days',
            'number' => '+28%'
        ),
        array(
            'color' => 'success',
            'icon' => 'icons/duotune/art/art005.svg',
            'title' => 'Navigation optimization',
            'text' => 'Due in 2 Days',
            'number' => '+50%'
        ),
        array(
            'color' => 'danger',
            'icon' => 'icons/duotune/communication/com012.svg',
            'title' => 'Rebrand strategy planning',
            'text' => 'Due in 5 Days',
            'number' => '-27%'
        ),
        array(
            'color' => 'info',
            'icon' => 'icons/duotune/communication/com012.svg',
            'title' => 'Product goals strategy',
            'text' => 'Due in 7 Days',
            'number' => '+8%'
        )
    );
?>

<!--begin::List Widget 6-->
<div class="card {{ $class }}">
    <!--begin::Header-->
    <div class="card-header border-0">
        <h3 class="card-title fw-bolder text-dark">Notifikasi</h3>
    </div>
    <!--end::Header-->

    <!--begin::Body-->
    <div class="card-body pt-0">
        @foreach(SampleDataController::getNotifications() as $row)
            <!--begin::Item-->
            <div class="d-flex align-items-center bg-light-{{ $row['color'] }} rounded p-5 {{ util()->putIf(next($listRows), 'mb-7') }}">
                <!--begin::Icon-->
                <span class="svg-icon svg-icon-{{ $row['color'] }} me-5">
                    {!! theme()->getSvgIcon($row['icon'], "svg-icon-1"); !!}
                </span>
                <!--end::Icon-->

                <!--begin::Title-->
                <div class="flex-grow-1 me-2">
                    <a href="{{ url($row['link']) }}" class="fw-bolder text-gray-800 text-hover-primary fs-6">{{ $row['title'] }}</a>

                    <span class="text-muted fw-bold d-block">{{ $row['text'] }}</span>
                </div>
                <!--end::Title-->
            </div>
            <!--end::Item-->
        @endforeach
    </div>
    <!--end::Body-->
</div>
<!--end::List Widget 6-->
