/*global */
;(function() {
  window.site = {
    config: {},
    models: {},
    views: {},
    collections: {},
    routes: {},
  }

  $(document).ready(function() {
    initControls()

    function initControls() {
      if ($(".editor").length) {
        initTinymce(
          ".editor",
          "formatselect | styleselect | bold italic | subscript superscript | bullist numlist outdent indent | link unlink | alignleft aligncenter alignright alignjustify | table | nonbreaking | image | code | removeformat | media | template",
          300
        )
      }

      if ($(".editor--small").length) {
        initTinymce(
          ".editor--small",
          "formatselect | styleselect | bold italic | subscript superscript | bullist numlist outdent indent | link unlink | nonbreaking | code | removeformat | media | template ",
          200
        )
      }

      if ($(".editor--small--nowrap").length) {
        initTinymce(
          ".editor--small--nowrap",
          "formatselect | styleselect | bold italic | subscript superscript | bullist numlist outdent indent | link unlink | nonbreaking | code | removeformat | media | template ",
          200,
          false
        )
      }

      if ($(".editor--tiny").length) {
        initTinymce(".editor--tiny", " code | removeformat", 20)
      }

      // linked Items
      $(".chosen_links").chosen()
      $(".datepicker").datepicker({ dateFormat: "dd-mm-yy" })

      // Set the popover default content
      $(".image-preview").popover({
        trigger: "hover",
        html: true,
        title: "<strong>Preview</strong>",
        content: "There's no image",
        placement: "bottom",
      })
    }

    //// Linked Items
    var modal_linked_module_type
    var modal = $("#myModal")

    $("body").on("click", ".link-add-button", function(e) {
      // Show the related modal
      e.preventDefault()

      var lang = window.default_lang
      modal_linked_module_type = $(this).attr("data-linked-module-type")

      console.log("modal_linked_module_type :" + modal_linked_module_type)

      var item_id = $(this).attr("data-item-id")

      if (!item_id) item_id = null

      $.ajax({
        type: "GET",
        url:
          window.base_url +
          "/icontrol/items/custom_view/" +
          module_type +
          "/get/" +
          lang +
          "/links/" +
          item_id +
          "/" +
          modal_linked_module_type,
        success: function(data) {
          // data = rendered blade <ul>
          $("#myModal .load_modal").html(data)

          modal.modal()

          modal.on("shown.bs.modal", function() {
            //check boxes that were checked in #selected
            var checked_boxes = $("#selected-" + modal_linked_module_type).find(
              "input:checkbox:checked"
            )
            checked_boxes.each(function() {
              console.log(modal_linked_module_type)
              console.log("input-" + this.id)
              document.getElementById("input-" + this.id).checked = true
            })
          })

          $("#myModal").on("click", "input:checkbox", function() {
            link_id = $(this).attr("data-link-id")
            console.log()
            //input to checked
            //enable label
            if (this.checked) {
              $("#li-" + link_id).css("display", "")
              $("#" + link_id).attr("checked", "checked")
            } else {
              document.getElementById(link_id).removeAttribute("checked")
              $("#li-" + link_id).css("display", "none")
            }
          })

          modal.on("hidden.bs.modal", function() {
            $("#myModal .load_modal").data("")
          })
        },
        error: function(err) {
          console.log("GET failed")
          console.log(err)
        },
      })
    })

    $("body").on("click", ".create-link-button", function(e) {
      // get form
      // append to modal new-link-form
      $(this).hide()
      var lang = window.default_lang
      var linked_module_type = $(this).attr("data-linked-module-type")
      if (linked_module_type) {
        $.ajax({
          type: "GET",
          url:
            window.base_url +
            "/icontrol/items/custom_view/" +
            linked_module_type +
            "/get/" +
            lang +
            "/form",
          success: function(data) {
            $("#myModal .modal-body").append(data)
            modal.on("hidden.bs.modal", function() {
              $("#myModal .modal-body").data("")
            })
          },
          error: function(data) {
            console.log(data)
          },
        })
      }
    })

    $("body .modal").on("submit", "form", function(e) {
      e.preventDefault()
      console.log(this)

      //send POST to server
      var form_modal = this
      var lang = window.default_lang
      var module_type = $(this).attr("data-module-type")
      console.log(module_type)

      if (module_type) {
        $.ajax({
          type: "POST",
          url:
            window.base_url +
            "/icontrol/items/" +
            module_type +
            "/post/" +
            lang,
          data: $(this).serialize(),
          success: function(data) {
            console.log("post recieved")
            console.log("response: ")
            console.log(data)

            if (data.valid) {
              $("#myModal .create-link-button").show()
              $("#myModal form").remove()
              // prepend success flash
              $("#myModal .alert").remove()
              var flash = $("<div>", { class: "alert alert-success" })
              flash.html(data.flash)
              $("#myModal .modal-body").prepend(flash)

              // Append new link to output
              var selected_output_ul = $("#selected-" + data.module_type)
              var display_new_link = $("<li>", {
                id: "li-link-" + data.id,
                class: "label label-primary",
              })
              display_new_link.html(data.description + " ")
              var display_new_link_checkbox = $("<input>", {
                id: "link-" + data.id,
                type: "hidden",
                name: "linked_items_" + module_type + "[" + data.id + "]",
                value: data.id,
                checked: true,
                class: "hide",
              })
              var remove_link_div = $("<div>", {
                class: "remove-link",
                style: "display: inline-block",
              })
              var remove_link_icon = $("<i>", {
                class: "fa fa-times-circle",
                "aria-hidden": "true",
              })
              remove_link_div.append(remove_link_icon)

              // <div class="remove-link" style="display: inline-block;"><i class="fa fa-times-circle" aria-hidden="true"></i></div>
              display_new_link.append(display_new_link_checkbox)
              display_new_link.append(remove_link_div)
              selected_output_ul.append(display_new_link)

              // Append new link to modal
              var modal_li = $("<li>")
              var modal_checkbox = $("<input>", {
                id: "input-link-" + data.id,
                type: "checkbox",
                "data-link-id": "link-" + data.id,
                checked: true,
              })
              var modal_label = $("<label>", {
                for: "input-link-" + data.id,
                text: data.description,
              })
              modal_li.append(modal_checkbox, modal_label)
              $(".modal .chosen_links").append(modal_li)
            } else {
              $("#myModal .alert").remove()
              for (prop in data) {
                // if (hasOwnProperty.call(data, prop)) {
                var flash = $("<div>", { class: "alert alert-danger" })
                flash.html(data[prop])
                // $("#myModal .panel-body").prepend(flash);
                $("#myModal .controls.draft_content").prepend(flash)
                // }
              }
            }
          },
          error: function(err) {
            console.log("post failed")
            console.log(err)
          },
        })
      }
      //if success append and select in checkbox list
    })

    $("body .selected-links").on("click", ".remove-link", function(e) {
      $(this)
        .siblings("input")
        .prop("checked", false)
      $(this)
        .parent("li")
        .css("display", "none")
      console.log($(this).siblings("input"))
    })

    ////////// OVERVIEW events
    $(document).on("change", ".overview .links", function(event) {
      var cat = $(this).val()
      var module = window.module_type

      $.ajax({
        type: "POST",
        url: window.base_url + "/icontrol/items/" + module + "/overviewdata",
        dataType: "text",
        data: { cat: cat },
        success: function(data) {
          if (data != "NOK") {
            $("#posts").replaceWith(data)
            initSortable()
          }
        },
        error: function(data) {
          console.log(data)
        },
      })
    })

    $(document).on("click", "#posts .publish", function(event) {
      var my_id = $(this).attr("data-id")
      var my_status = $(this).attr("data-publish")
      var module = window.module_type

      $.ajax({
        type: "POST",
        url: window.base_url + "/icontrol/items/" + module + "/publish",
        dataType: "text",
        data: { id: my_id, status: my_status },
        success: function(data) {
          if (data == "OK") {
            $("#posts .publish[data-id='" + my_id + "']").toggle()
          }
        },
        error: function(data) {
          console.log(data)
        },
      })
    })

    $(document).on("click", "#posts .delete", function(event) {
      var my_id = $(this).attr("data-id")
      var my_title = $(this).attr("data-title")
      var module = window.module_type
      if (confirm("You are about to remove: " + my_title)) {
        console.log(window.base_url + "/icontrol/items/" + module + "/delete")
        console.log()
        $.ajax({
          type: "POST",
          url: window.base_url + "/icontrol/items/" + module + "/delete",
          dataType: "text",
          data: { id: my_id },
          success: function(data) {
            if (data == "OK") {
              $("#posts .delete[data-id='" + my_id + "']")
                .closest("tr")
                .remove()
            }
          },
          error: function(data) {
            console.log(data)
          },
        })
      }
    })

    initSortable()

    function initSortable() {
      $(".hoverimg").hide()

      $("#posts.sortable tr").mouseover(function() {
        $(this)
          .find(".hoverimg")
          .show()
      })

      $("#posts.sortable tr").mouseout(function() {
        $(this)
          .find(".hoverimg")
          .hide()
      })

      $("#posts.sortable tbody").sortable({
        axis: "y",
        placeholder: "ui-state-highlight",
        stop: function(event, ui) {
          var my_id = ui.item.data("id")
          var module = window.module_type
          var to_index = $(ui.item)
            .parent()
            .children()
            .index(ui.item)
          $.ajax({
            type: "POST",
            url: window.base_url + "/icontrol/items/" + module + "/sortitems",
            dataType: "text",
            data: { id: my_id, index: to_index },
            success: function(data) {
              if (data == "OK") {
                for (var i = 0; i < $("#posts tr").length; i++) {
                  $("#posts tr")
                    .eq(i)
                    .find(".row-index")
                    .html(i + 1)
                }
              }
            },
            error: function(data) {
              console.log(data)
            },
          })
        },
      })

      $("#posts").disableSelection()

      $("#blocks").sortable({
        placeholder: "ui-state-highlight",
        helper: "clone",
      })
    }

    function setInputStatus() {
      $("input[type=checkbox]").each(function() {
        if (this.name.indexOf("_enabled") > 0) {
          $(
            'input[name="prop[' + this.name.replace("_enabled", "") + ']"]'
          ).prop("disabled", !this.checked)
        }
      })
    }

    setInputStatus()

    $(document).on("change", "input[type=checkbox]", function(event) {
      setInputStatus()
    })

    function initTinymce(selector, toolbar, height, wrap) {
      var template_data = [
        { title: "Add article more", content: "<br/>##more##<br/>" },
        { title: "Add contest block", content: "<br/>##contest##<br/>" },
      ]

      tinymce.init({
        element_format: "html",
        selector: selector,
        fix_list_elements: true,
        height: height,
        menubar: false,
        statusbar: false,
        entity_encoding: "raw",
        plugins:
          "paste,table,nonbreaking,link,code,image,contextmenu,media,template",
        templates: template_data,
        contextmenu: "link image inserttable | cell row column deletetable",
        paste_as_text: true,
        relative_urls: false,
        forced_root_block: wrap === undefined ? "p" : "",
        skin_url: window.base_url + "/admin/components/tinymce/skins/typicms",
        inline_styles: true,
        extended_valid_elements:
          "iframe[src|frameborder|style|scrolling|class|width|height|name|align], iframe",
        image_class_list: [
          { title: "None", value: "" },
          { title: "Left", value: "left" },
          { title: "Right", value: "right" },
        ],
        file_browser_callback: function(field_name, url, type, win) {
          // Help : http://www.tinymce.com/forum/viewtopic.php?id=30861&p=2
          tinymce.activeEditor.windowManager.open(
            {
              title: "Choose image",
              url:
                window.base_url +
                "/icontrol/files/image/popupmanager/" +
                window.module_type +
                "/" +
                "noinput" +
                "/" +
                "novalue",
              width: 835,
              height: 550,
            },
            {
              oninsert: function(url) {
                fieldElm = win.document.getElementById(field_name)
                fieldElm.value = url
                // Bellow code doesn't work anymore with TinyMCE 4.1.7
                // so width and height fields are no more automatically set
                // if ("createEvent" in document) {
                //     var evt = document.createEvent("HTMLEvents");
                //     evt.initEvent("change", false, true);
                //     fieldElm.dispatchEvent(evt);
                // } else {
                //     fieldElm.fireEvent("onchange");
                // }
              },
            }
          )
        },
        // statusbar: false,
        block_formats:
          "Paragraph=p;Code=pre;Blockquote=blockquote;Header 1=h1;Header 2=h2;Header 3=h3;Header 4=h4;Header 5=h5;Header 6=h6",
        style_formats: [
          { title: "Small text", inline: "small" },
          {
            title: "Image Left",
            selector: "img",
            styles: { float: "left", margin: "0 20px 20px 0" },
          },
          {
            title: "Image Right",
            selector: "img",
            styles: { float: "right", margin: "0 0 20px 20px" },
          },
          { title: "File (link)", selector: "a", classes: "file" },
          { title: "Button (link)", selector: "a", classes: "btn btn-default" },
        ],
        content_css: [
          window.base_url + "/admin/components/tinymce/css/tiny_mce.css",
          window.base_url + "/css/app.css",
        ],
        toolbar: toolbar,
        language_url:
          window.base_url + "/admin/components/tinymce/langs/" + "en" + ".js",
      })
    }

    //console.log("locked and loaded");
    //my_content[seo_title]
    $(document).on("blur", "form #my_content\\[seo_title\\]", function(event) {
      var input_text = $(this).val()
      var title_seo = $(this)

      $.ajax({
        type: "POST",
        url: window.base_url + "/icontrol/geturlfriendlytext",
        data: { text: input_text },
        success: function(data) {
          title_seo
            .closest("form")
            .find(".slug")
            .val(data)
        },
        error: function(data) {
          console.log(data)
        },
      })
    })

    $(document).on("click", "#show_draft", function(event) {
      event.preventDefault()
      $(".draft_content").show()
      $("#show_draft").addClass("active")
      $(".online_content").hide()
      $("#show_online").removeClass("active")
    })
    $(document).on("click", "#show_online", function(event) {
      event.preventDefault()
      $(".draft_content").hide()
      $("#show_draft").removeClass("active")
      $(".online_content").show()
      $("#show_online").addClass("active")
    })

    function warn_save() {
      var status = $("#input_changes").val()
      if (status == "true") {
        return confirm(
          "This action will undo the changes you made. Please save a draft first if you want to keep your changes. Continue?"
        )
      }
      return true
    }

    $(document).on("click", ".lang_content", function(event) {
      return warn_save()
    })

    $(document).on("click", "#overview", function(event) {
      return warn_save()
    })

    $(document).on("change", ":input", function(event) {
      $("#input_changes").val("true")
    })

    $(document).on("change", "#filelist :checkbox", function(event) {
      var value = $(this).is(":checked")
      var id = $(this).attr("data-id")
      var module = $(this).attr("value")
      $.ajax({
        type: "POST",
        url: window.base_url + "/icontrol/files/file_assign_cat",
        dataType: "text",
        data: { id: id, module: module, status: value },
        success: function(data) {
          if (data == "OK") {
            //nothing much to do
          }
        },
        error: function(data) {
          console.log(data)
        },
      })
    })

    $(document).on("click", "#filelist .remove_image", function(event) {
      event.preventDefault()
      var id = $(this).attr("data-id")
      var mode = $("#myDropzone").attr("data-mode")
      var manager_type = $("#myDropzone").attr("data-manager-type")
      var input = $("#myDropzone").attr("data-input")
      var module = window.module_type
      $.ajax({
        type: "POST",
        url: window.base_url + "/icontrol/files/remove",
        dataType: "json",
        data: { id: id },
        success: function(data) {
          $.ajax({
            type: "GET",
            url:
              window.base_url +
              "/icontrol/files/" +
              manager_type +
              "/getlist/" +
              mode +
              (mode != "manager" ? "/" + input + "/" + module : ""),
            data: { image_id: 1 },
          }).done(function(html) {
            $("#filelist").html(html)
          })
        },
        error: function(data) {
          console.log(data)
        },
      })
    })

    $(document).on("click", "#filelist .purge", function(event) {
      event.preventDefault()
      var mode = $("#myDropzone").attr("data-mode")
      var input = $("#myDropzone").attr("data-input")
      var manager_type = $("#myDropzone").attr("data-manager-type")
      var module = window.module_type
      $.ajax({
        type: "POST",
        url: window.base_url + "/icontrol/files/" + manager_type + "/purge",
        dataType: "json",
        data: {},
        success: function(data) {
          $.ajax({
            type: "GET",
            url:
              window.base_url +
              "/icontrol/files/" +
              manager_type +
              "/getlist/" +
              mode +
              (mode != "manager" ? "/" + input + "/" + module : ""),
            data: { image_id: 1 },
          }).done(function(html) {
            $("#filelist").html(html)
          })
        },
        error: function(data) {
          console.log(data)
        },
      })
    })

    var acceptedFiles = $("#myDropzone").attr("data-accepted-files")
    var maxFileSize = $("#myDropzone").attr("data-max-filesize")

    Dropzone.options.myDropzone = {
      paramName: "file", // The name that will be used to transfer the file
      maxFilesize: maxFileSize, // MB
      acceptedFiles: acceptedFiles,
      createImageThumbnails: true,
      init: function() {
        // Register for the thumbnail callback.
        // When the thumbnail is created the image dimensions are set.
        this.on("thumbnail", function(file) {
          var minImageWidth = $("#myDropzone").attr("data-width")
          var minImageHeight = $("#myDropzone").attr("data-height")

          if (
            (minImageWidth != "" && minImageWidth != null) ||
            (minImageHeight != "" && minImageHeight != null)
          ) {
            // Do the dimension checks you want to do
            if (file.width < minImageWidth || file.height < minImageHeight) {
              file.rejectDimensions()
            } else {
              file.acceptDimensions()
            }
          } else {
            file.acceptDimensions()
          }
        })

        this.on("queuecomplete", function() {
          var mode = $("#myDropzone").attr("data-mode")
          var input = $("#myDropzone").attr("data-input")
          var manager_type = $("#myDropzone").attr("data-manager-type")
          var input_type = $("#myDropzone").attr("data-input-type")
          var module = $("#myDropzone").attr("data-module")
          url =
            window.base_url + "/icontrol/files/" + manager_type + "/getlist/"
          this.removeAllFiles()
          $.ajax({
            type: "GET",
            url:
              url +
              mode +
              (mode != "manager"
                ? "/" + input + "/" + module + "/" + input_type
                : ""),
            data: {},
          }).done(function(html) {
            $("#filelist").html(html)
          })
        })
      },
      // Instead of directly accepting / rejecting the file, setup two
      // functions on the file that can be called later to accept / reject
      // the file.
      accept: function(file, done) {
        var manager_type = $("#myDropzone").attr("data-manager-type")

        if (manager_type == "file") {
          return done()
        }

        file.acceptDimensions = done
        file.rejectDimensions = function() {
          done("Invalid dimension.")
          alert(
            "Upload " + file.name + " is too small. This file will be skipped."
          )
        }
        // Of course you could also just put the `done` function in the file
        // and call it either with or without error in the `thumbnail` event
        // callback, but I think that this is cleaner.
      },
    }

    $(document).on("click", ".showmanager", function() {
      var module_type = window.module_type
      var input_id = $(this).attr("data-input")
      var manager_type = $(this).attr("data-manager-type")
      var type = $(this).attr("data-type")
      var value =
        $("#" + input_id).val() !== "" ? $("#" + input_id).val() : "novalue"

      var url =
        window.base_url +
        "/icontrol/files/" +
        manager_type +
        "/manager/" +
        module_type
      var input_type = "all"
      if (type != null && type != "") {
        input_type = type
      }
      url = url + "/" + input_type + "/" + input_id + "/" + value

      $.get(url, function(data) {
        $("#myModal .load_modal").html(data)
        $("#myDropzone").dropzone({ url: $("#myDropzone").attr("action") })
        modal.modal()

        modal.on("hidden.bs.modal", function() {
          $("#myModal .modal-body").data("")
        })
      })
    })

    $(document).on("click", "#filelist .select_image", function(event) {
      event.preventDefault()

      var input_id = $(this).attr("data-input")
      var manager_type = $(this).attr("data-manager-type")
      var mode = $("#myDropzone").attr("data-mode")

      if (mode == "popup" && input_id == "noinput") {
        //in editor mode
        path =
          window.base_url +
          "/uploads/" +
          manager_type +
          "/" +
          $(this).attr("data-file")
        var TinyMCEWindow = top.tinymce.activeEditor.windowManager
        TinyMCEWindow.getParams().oninsert(path)
        TinyMCEWindow.close()
      }
      $("#" + input_id).val($(this).attr("data-id"))
      //$('#img_'+input_id).attr("src",window.base_url + '/uploads/'+manager_type+'/' + $(this).attr("data-file")).show();
      $("#file_" + input_id)
        .attr(
          "href",
          window.base_url +
            "/uploads/" +
            manager_type +
            "/" +
            $(this).attr("data-file")
        )
        .html($(this).attr("data-file"))
        .show()
      var file_name = $(this).attr("data-file")
      //set preview popover for images
      var container = ".cnt_" + input_id

      if (manager_type == "image") {
        $.ajax({
          type: "GET",
          url:
            window.base_url +
            "/icontrol/files/getimagecontainer/" +
            $(this).attr("data-id") +
            "/" +
            $("#myDropzone").attr("data-input-type"),
          data: {},
        }).done(function(html) {
          $(container + " .image-preview-input-title").text("Change")
          $(container + " .image-preview-clear").show()
          $(container + " .image-preview-filename").val(file_name)
          $(container + " .image-preview")
            .attr("data-content", html)
            .popover("show")
        })
      } else {
        $(container + " .image-preview-filename").val(file_name)
      }

      module_type = window.module_type
      value =
        $("#" + input_id).val() !== "" ? $("#" + input_id).val() : "novalue"

      modal.modal("hide")
      /*$.get( window.base_url + '/icontrol/files/'+manager_type+'/manager/' + module_type + '/' + input_id + '/' + value, function( data ) {

            modal.modal();
            modal.on('shown.bs.modal', function(){
                $('#myModal .load_modal').html(data);
            });
            modal.on('hidden.bs.modal', function(){
                $('#myModal .modal-body').data('');
            });
        });*/

      //modal.removeClass("in");
    })

    $(document).on("click", "#filelist .detach_image", function(event) {
      event.preventDefault()
      var input = $(this).attr("data-input")
      var module_type = window.module_type
      var manager_type = $(this).attr("data-manager-type")

      var value = "novalue"
      $("#" + input).val("")
      //$('#img_'+input).hide();
      clearImagePreview("cnt" + input)
      $.get(
        window.base_url +
          "/icontrol/files/" +
          manager_type +
          "/manager/" +
          module_type +
          "/" +
          input +
          "/" +
          value,
        function(data) {
          modal.modal()
          modal.on("shown.bs.modal", function() {
            $("#myModal .load_modal").html(data)
          })
          modal.on("hidden.bs.modal", function() {
            $("#myModal .modal-body").data("")
          })
        }
      )
    })

    $("#ddl_add_block").change(function(event) {
      var type = $("#ddl_add_block").val()
      var count = $("#blocks div[data-type=" + type + "]").length
      var allowed = $(this)
        .find(":selected")
        .attr("data-amount")
      if (allowed) {
        if (count >= allowed) {
          $("#btn_add_block").prop("disabled", true)
          return
        }
      }
      $("#btn_add_block").prop("disabled", false)
    })

    $(document).on("click", "#btn_add_block", function(event) {
      event.preventDefault()
      var type = $("#ddl_add_block").val()
      var module_type = window.module_type
      var id = $(this).attr("data-id")
      var lang = $(this).attr("data-lang")
      var action = $(this).attr("data-action")
      var count = $("#blocks div[data-type=" + type + "]").length
      var version = $(this).attr("data-version")
      console.log(count)
      $.ajax({
        type: "POST",
        url:
          window.base_url + "/icontrol/items/" + module_type + "/renderblock",
        data: {
          type: type,
          module_type: module_type,
          lang: lang,
          id: id,
          action: action,
          count: count,
          version: version,
        },
        success: function(data) {
          var count = $("#blocks div[data-type=" + type + "]").length
          $("#blocks").append(data)
          $("#block_" + type + "_" + count).scrollTo()
          initControls()
        },
        error: function(data) {
          console.log(data)
        },
      })
    })

    $.fn.scrollTo = function(speed) {
      if (typeof speed === "undefined") speed = 1000

      $("html, body").animate(
        {
          scrollTop: parseInt($(this).offset().top),
        },
        speed
      )
    }

    $(document).on("click", ".close-preview", function() {
      $(".image-preview").popover("hide")
      // Hover befor close the preview
      $(".image-preview").hover(
        function() {
          $(".image-preview").popover("show")
        },
        function() {
          $(".image-preview").popover("hide")
        }
      )
    })

    function clearImagePreview(container) {
      $(container + " .image-preview")
        .attr("data-content", "")
        .popover("hide")
      $(container + " .image-preview-filename").val("")
      $(container + " .image-preview-clear").hide()
      $(container + " .image-preview-input input:file").val("")
      $(container + " .image-preview-input-title").text("Browse")
    }

    // Clear event
    $(document).on("click", ".image-preview-clear", function() {
      var input = $(this).attr("data-input")
      var container = ".cnt_" + input
      clearImagePreview(container)
      $("#" + input).val("")
    })

    //Delete content block
    $(document).on("click", ".delete_block", function() {
      if (
        confirm(
          "Do you want to delete this block? Save this item to confirm removal of this block."
        )
      ) {
        $(this)
          .closest(".blockcontent_block")
          .remove()
        var types = []
        types = $(".blockcontent_block")
          .map(function() {
            value = $(this).attr("data-type")
            if (types.hasOwnProperty(value)) return null

            types[value] = true
            return value
          })
          .get()

        types.forEach(function(type) {
          $(".blockcontent_block[data-type=" + type + "]").each(function(
            index,
            value
          ) {
            console.log(index)
            $(value).attr("id", "block_" + type + "_" + index)
            $(value)
              .find("input, select")
              .each(function(inputindex, input) {
                if ($(input).attr("name") != undefined) {
                  var input_name = "block_content[" + type + "_" + index + "]"
                  console.log($(input).attr("name"))
                  $(input).attr(
                    "name",
                    input_name +
                      $(input)
                        .attr("name")
                        .substring(
                          $(input)
                            .attr("name")
                            .indexOf("]") + 1
                        )
                  )
                }
              })
          })
        })
      }
    })

    // MULTIPLE IMAGES
    $(document).on("click", ".multiple_control_add", function() {
      var amount_visible = $(this)
        .closest(".multiple_container")
        .find(".row:visible").length
      $(this)
        .closest(".multiple_container")
        .find(".row:eq(" + amount_visible + ")")
        .show()
      if (amount_visible >= $(this).attr("data-amount")) {
        $(this).hide()
      }
    })
  })
})()
