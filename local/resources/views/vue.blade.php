@extends('layouts.app')
@section('title', 'Welcome')
@section('content')

<div class="col-md-8 col-md-offset-2 mh-450" id="manage-vue">
    <div class="panel panel-default">
        <div class="panel-heading">Country</div>
        <div class="box">
            <div class="box-divider m-a-0"></div>
            <div class="panel-body">
            @{{ data.baseUrl }}
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
@endsection
@section('pageScript')
<script  src="{!! URL::asset('local/assets/js/vue.min.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
<script  src="{!! URL::asset('local/assets/js/vue-resource.min.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
<script type="text/javascript">
var data = { baseUrl:baseUrl }
var vm = new Vue({
  el: '#manage-vue',
  data:data,
  ready : function()
  {
    console.log("sdfsdfs");
    this.getVueItems(this.baseUrl);
  },

  methods : {
        getVueItems: function()
        {
          this.$http.get(+'test?page='+page).then((response) => {

            this.$set('items', response.data.data.data);
            this.$set('pagination', response.data.pagination);
          });
        }
    }
});
</script>
@stop