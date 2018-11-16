@extends(viewPrefixCmsNamespace('admin.layouts.template'))

@section('content')

    <div class="content">
        <div class="row">
            <div class="col-md-1" style="background: #FFF">
                <ul class="nav flex-column">
                    @foreach($langs as $lang)
                        <li class="nav-item">
                            <a href="{{ route('icontrol.translation.show', $lang) }}" class="nav-link js-translation-tabs active">{{ strtoupper($lang) }}</a>
                        </li>
                    @endforeach
                </ul>

            </div>
            <div class="col-md-11 js-translation-content-tab">

            </div>

        </div>

    </div>

    <script>
        $(function() {
          $(document).on('click', '.js-translation-tabs', function(e) {
            e.preventDefault()

            var route = $(this).attr('href');

            $('.js-translation-tabs').removeClass('active')
            $(this).addClass('active')

            $.ajax({
              url: route,
              type: 'GET',
              success: function(result) {
                $('.js-translation-content-tab').html(result.html);
              }
            })
          })

          $('.js-translation-tabs:first').click();

          $(document).on('click', '.js-open-page-translation', function(e) {
            e.preventDefault()

            $('.js-open-page-translation').removeClass('active');
            $(this).addClass('active');
            $('.js-saved-status').empty();

            $('.content-page-translation').hide();

            $('#' + $(this).data('target')).show();
          })

          $(document).on('submit', '.js-translation-form', function(e) {
            e.preventDefault()

            var method = $(this).attr('method')
            var action = $(this).attr('action')
            var datas = $(this).serialize()
            var statustarget = $(this).data('statustarget')

            $.ajax({
              url: action,
              type: method,
              data: datas,
              success: function(result) {
                $('#' + statustarget).text('Saved !')
              }
            })

          })
        })
    </script>

@endsection