<div class="app-footer clearfix">
    <div class="padding">
        <div class="white r box-shadow-z0 m-b p-md">
            <div class="footer p-a-md">
                <div class="text-center">
                    <div class="nav m-y">
                    <a class="nav-link m-r text-info" target="_blank" href="{{ config('services.SITE_DETAILS.SITE_DEPOSIT_PDF') }}"><span>{{ config('services.SITE_DETAILS.SITE_DEPOSIT_TEXT') }}</span></a> 
                    <a class="nav-link m-r text-info" target="_blank" href="{{ config('services.SITE_DETAILS.SITE_VIDEO1') }}"><span>{{ config('services.SITE_DETAILS.SITE_VIDEO1_TEXT') }}</span></a> 
                    <a class="nav-link m-r text-info" target="_blank" href="{{ config('services.SITE_DETAILS.SITE_VIDEO2') }}"><span>{{ config('services.SITE_DETAILS.SITE_VIDEO2_TEXT') }}</span></a> 
                    <a class="nav-link m-r text-info" target="_blank" href="{{ config('services.SITE_DETAILS.SITE_FOOTER_LINK') }}"><span>{{ config('services.SITE_DETAILS.SITE_FOOTER_TEXT') }}</span></a> 
                    <a class="nav-link m-r text-info" target="_blank" href="{{ config('services.SITE_DETAILS.SITE_AGGREMENT') }}"><span>{{ config('services.SITE_DETAILS.SITE_AGGREMENT_TEXT') }}</span></a>
                    <a class="nav-link m-r text-info" target="_blank" href="{{ config('services.SITE_DETAILS.SITE_FOOTER_LINK_PRESENTATION') }}"><span>{{ config('services.SITE_DETAILS.SITE_FOOTER_TEXT_PRESENTATION') }}</span></a>
                    <a class="nav-link m-r text-info" target="_blank" href="{{ config('services.SITE_DETAILS.SITE_FOOTER_LINK_COMPLIANCE') }}"><span>{{ config('services.SITE_DETAILS.SITE_FOOTER_TEXT_COMPLIANCE') }}</span></a>
                    <a class="nav-link m-r text-info" target="_blank" href="{{ config('services.SITE_DETAILS.SITE_FOOTER_LINK_FAQ') }}"><span>{{ config('services.SITE_DETAILS.SITE_FOOTER_TEXT_FAQ') }}</span></a>
                    </div>
                </div>
                <div class="b b-b m-y-md"></div>
                <div class="row footer-bottom">
                    <div class="col-sm-6"><strong class="text-muted">{{ \Carbon\Carbon::now()->toDayDateTimeString() }}  {{ Config::get('app.timezone') }} | {{ dispayTimeStamp(\Carbon\Carbon::now())->toDayDateTimeString() }} {{ Config::get('app.timezone_display2') }} | Version 2.0</strong>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-right text-xs-left">Developed by <strong><a href="{{ config('services.SITE_DETAILS.SITE_CREATE_COMPANY_WEBSITE') }}" target="_blank">{{ config('services.SITE_DETAILS.SITE_CREATE_COMPANY') }}</a></strong><a ui-scroll-to="content" title="Go Top"><i class="fa fa-long-arrow-up p-x-sm"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>