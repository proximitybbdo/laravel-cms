@foreach ($files as $file)

  @if ($manager_type == 'file')
  <div class="col-md-2  {{ $file->id == $value ? 'active' : '' }}">
                            <div class="options-container">
                                <div class="options-overlay bg-black-75">
                                    <div class="options-overlay-content">
                                        <h3 class="h4 text-white mb-2">Main Title</h3>
                                        <h4 class="h6 text-white-75 mb-3">More Information</h4>
                                        <a class="btn btn-sm btn-primary" href="javascript:void(0)">
                                            <i class="fa fa-pencil-alt mr-1"></i> Edit
                                        </a>
                                        <a class="btn btn-sm btn-danger" href="javascript:void(0)">
                                            <i class="fa fa-times mr-1"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
  @else
  <div class="col-md-2 animated fadeIn {{ $file->id == $value ? 'active' : '' }}">
  @endif
    <div class="options-container">

      @if ($manager_type == 'image')
        <img class="img-fluid options-item" src="{{ url("uploads/$manager_type/thumbs/" . $file->file) }}">
      @endif

      @if ($manager_type == 'file')
      <h2><i class="fa fa-file-pdf-o"></i></h2><a href="{{ url("uploads/$manager_type/" . $file->file) }}" target="_blank">{{ $file->file }}</a>
      @endif

      <div class="options-overlay bg-black-75">
        <div class="options-overlay-content">
          <h4 class="h6 text-white-75 mb-4">{{ $file->file }}</h4>

          @if ($mode != "popup")

            <a href="#" class="remove_image" data-id="{{ $file->id }}"><i class="fa fa-trash-o"></i></a>
              @if (in_array($file->id, []))  {{-- if(in_array($file->id, [$content_links])) --}}
                <i class="fa fa-link" title="linked"></i>
              @endif
          @endif

          @if ($mode === "popup")

            <a href="#" class="btn btn-sm btn-primary select_image" data-manager-type="{{ $manager_type }}" data-module="{{ $module_type }}" data-id="{{ $file->id }}" data-file="{{ $file->file }}" data-input="{{ $input_id }}"><i class="fa fa-check"></i>
              <i class="fa check-circle mr-1"></i>
            </a>
            @if ($file->id == $value)
                <a href="#" class="btn btn-sm btn-danger detach_image" data-manager-type="{{ $manager_type }}" data-module="{{ $module_type }}" data-id="{{ $file->id }}" data-input="{{ $input_id }}">
                <i class="fa fa-times mr-1"></i>
                </a>
            @endif
          @endif
          @if ($mode != "popup")

            @foreach ($categories as $category)
            <div>
              <input id="{{ $file->id . '-' . $category }}" type="checkbox" name="{{ $file->id . '-' . $category }}" value="{{ $category }}" data-id="{{ $file->id }}" {{ in_array($category, $file->modules()->pluck('module_type')->all()) ? "checked" : "" }}/>
              <label for="{{ $file->id . '-' . $category }}">{{ $category }}</label>
            </div>
            @endforeach
          @endif
          </div>
      </div>
    </div>
  </div>
@endforeach
