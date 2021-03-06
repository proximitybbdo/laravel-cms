<div class="block-content">
  <div class="form-group">
    <div class="controls draft_content">
      @if (Sentinel::hasAnyAccess( [strtolower($module_type) . '.create', strtolower($module_type) . '.update'] ) || Sentinel::inRole('admin'))
        <button type="submit" name="draft" class="btn btn-warning">
          Save Draft
        </button>

        @if ($preview_link != null)
          <a href="{{ $preview_link }}?preview=true" target="_blank" class="btn btn-primary">Preview draft</a>
        @endif
      @endif

      @if (Sentinel::hasAccess( strtolower($module_type) . '.publish') || Sentinel::inRole('admin'))
        <button type="submit" name="publish" class="btn btn-success">
            Publish Draft
        </button>
      @endif
    </div>
  </div>
</div>
