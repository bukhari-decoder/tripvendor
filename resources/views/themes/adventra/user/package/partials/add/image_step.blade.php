<div id="stepImages" class="step-content-section d-none">
    @if(isAiAccess())
        <div class="d-flex justify-content-end align-items-center pb-2">
            <button type="button" class="btn btn-soft-success btn-sm" data-bs-toggle="offcanvas" data-bs-target="#imagesGenerateOffcanvas" aria-controls="imagesGenerateOffcanvas"><i class="bi bi-lightning pe-1"></i>@lang('Use Ai')</button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="imagesGenerateOffcanvas" aria-labelledby="imagesGenerateOffcanvasLabel">
                <div class="offcanvas-header">
                    <h5 id="offcanvasRightLabel"><i class="bi-gear me-1"></i>@lang('Generate image with '. basicControl()->site_title.' AI assistant')</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                <span class="text-dark font-weight-bold">
                    @lang('Bring your tour packages to life with AI-generated images! 🖼️ Let us create stunning visuals to boost engagement and bookings. Ready to generate your images?')
                </span>

                    <div class="col-12 mb-4 mt-4">
                        <span class="text-cap text-body">@lang("Describe about your image")</span>
                        <textarea class="form-control" id="generateImageTitle" rows="4" placeholder="@lang('e.g. Tropical beach at sunset with turquoise water, white sand, palm trees, and happy couple walking.')" autocomplete="off"></textarea>
                    </div>

                    <div class="col-12 mb-4">
                        <span class="text-cap text-body">@lang("Image Type")</span>
                        <select class="form-control js-select" id="imageType">
                            <option value="thumbnail">@lang('Thumbnail')</option>
                            <option value="images">@lang('Images')</option>
                        </select>
                    </div>

                    <div class="col-12 mb-4 d-none" id="imageCountWrapper">
                        <span class="text-cap text-body">@lang("How many images?")<sub>@lang('(maximum 3 image)')</sub></span>
                        <input type="number" class="form-control" id="imageCount" placeholder="@lang('e.g. 3')" min="1" max="3" autocomplete="off">
                    </div>

                    <div class="row gx-2">
                        <div class="col">
                            <div class="d-grid">
                                <button type="button" class="btn btn-primary btn-sm" id="generateImageBtn">
                                    <i class="bi bi-lightning"></i> @lang('Generate')
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="previewImages"></div>
                </div>
            </div>
        </div>
    @endif

    <div class="card mb-3 mb-lg-5">
        <div class="card-body">
            <label class="form-label" for="packageThumbnail">@lang('Package Thumbnail')<span class="text-danger ps-1">*</span></label>
            <label class="form-check form-check-dashed" for="logoUploader" id="content_img">
                <img id="previewImage"
                     class="avatar-centered mb-2"
                     src="{{ asset("assets/admin/img/oc-browse-file.svg") }}"
                     alt="Image Preview" data-hs-theme-appearance="default">
                <span class="d-block">@lang("Browse your file here")</span>
                <input type="file" class="js-file-attach form-check-input" name="thumb"
                       id="logoUploader" data-hs-file-attach-options='{
                                                                      "textTarget": "#previewImage",
                                                                      "mode": "image",
                                                                      "targetAttr": "src",
                                                                      "allowTypes": [".png", ".jpeg", ".jpg"]
                                                                   }'>
            </label>
            <p class="pt-2">@lang('For better resolution, please use an image with a size of') {{ config('filelocation.package_thumb.size') }} @lang(' pixels.')</p>
            @error('thumb')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="card mb-3 mb-lg-5">
        <div class="card-body">
            <label class="form-label" for="packageImage">@lang('Package Images')<span class="text-danger ps-1">*</span></label>
            <div class="input-images" id="packageImage"></div>
            @if($errors->has('images'))
                <span class="invalid-feedback d-block">
                    <strong>{{ $errors->first('images') }}</strong>
                </span>
            @endif
            <p class="pt-2">@lang('For better resolution, please use an image with a size of') {{ config('filelocation.package.size') }} @lang(' pixels.')</p>
        </div>
    </div>
    <div class="bottom-area">
        <button type="button" class="btn btn-secondary btn-sm"
                data-hs-step-form-prev-options='{ "targetSelector": "#stepFacilityDetails" }'>
            <i class="bi-arrow-bar-left pe-1"></i>@lang('Previous')
        </button>
        <button type="submit" class="btn btn-primary btn-sm">@lang("Save")<i class="bi-check-circle ps-1"></i></button>
    </div>
</div>

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/global/css/image-uploader.min.css') }}">

    <style>

        .image-uploader{
            border:  .0625rem solid rgba(231, 234, 243, .7) !important;
        }
        #imagesGenerateOffcanvas {
            max-width: 1050px;
            width: 100%;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset('assets/global/js/image-uploader.min.js') }}"></script>
    <script>
        "use strict";
        document.getElementById('logoUploader').addEventListener('change', function() {
            let file = this.files[0];
            let reader = new FileReader();

            reader.onload = function(e) {
                let preview = document.getElementById('previewImage');
                preview.src = e.target.result;
                preview.style.maxWidth = '300px';
                preview.style.width = '100%';
                preview.style.height = '350px';
                preview.style.objectFit = 'contain';
            }

            reader.readAsDataURL(file);
        });
        $(document).ready(function() {
            $('.input-images').imageUploader();
        });


        document.addEventListener("DOMContentLoaded", function () {
            const imageTypeSelect = document.getElementById('imageType');
            const imageCountWrapper = document.getElementById('imageCountWrapper');

            function toggleImageCount() {
                if (imageTypeSelect.value === 'images') {
                    imageCountWrapper.classList.remove('d-none');
                } else {
                    imageCountWrapper.classList.add('d-none');
                }
            }

            imageTypeSelect.addEventListener('change', toggleImageCount);

            toggleImageCount();
        });

        document.addEventListener("DOMContentLoaded", function () {
            const generateBtn = document.getElementById('generateImageBtn');
            generateBtn.addEventListener('click', function () {
                const title = document.getElementById('generateImageTitle').value.trim();
                const imageType = document.getElementById('imageType').value;
                const imageCount = document.getElementById('imageCount')?.value || 1;

                if (!title) {
                    Notiflix.Notify.failure("Please enter a package title.");
                    return;
                }

                const formData = {
                    imageDescription: title,
                    image_type: imageType,
                    image_count: imageType === 'images' ? imageCount : 1
                };

                Notiflix.Loading.standard('Generating...');

                fetch("{{ route('user.ai.generate.image') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(formData)
                })
                    .then(response => response.json())
                    .then(data => {
                        Notiflix.Loading.remove();

                        if (data.status === 'success') {
                            Notiflix.Notify.success("Images generated successfully!");

                            const previewDiv = document.querySelector(".previewImages");
                            previewDiv.innerHTML = "";
                            data.image_data_uris.forEach((imageUri, index) => {

                                const wrapper = document.createElement("div");
                                wrapper.style.position = "relative";
                                wrapper.style.display = "inline-block";
                                wrapper.style.margin = "10px";
                                wrapper.style.maxWidth = "100%";

                                const img = document.createElement("img");
                                img.src = imageUri;
                                img.alt = `Generated Preview ${index + 1}`;
                                img.style.maxWidth = "300px";
                                img.style.border = "1px solid #ccc";
                                img.style.display = "block";
                                img.style.borderRadius = "6px";
                                img.style.height = "100%";

                                const downloadLink = document.createElement("a");
                                downloadLink.href = imageUri;
                                downloadLink.download = `generated-image-${index + 1}.png`;
                                downloadLink.title = "Download Image";
                                downloadLink.innerHTML = `<i class="fas fa-download"></i>`;
                                downloadLink.style.position = "absolute";
                                downloadLink.style.top = "10px";
                                downloadLink.style.right = "10px";
                                downloadLink.style.color = "#fff";
                                downloadLink.style.background = "rgba(0, 0, 0, 0.5)";
                                downloadLink.style.padding = "6px";
                                downloadLink.style.borderRadius = "50%";
                                downloadLink.style.textDecoration = "none";
                                downloadLink.style.fontSize = "18px";

                                wrapper.appendChild(img);
                                wrapper.appendChild(downloadLink);
                                previewDiv.appendChild(wrapper);
                            });

                        } else {
                            Notiflix.Notify.failure(data.message || "Failed to generate images.");
                        }

                    })
                    .catch(error => {
                        Notiflix.Loading.remove();
                        Notiflix.Notify.failure("Something went wrong while generating images.");
                    });
            });
        });
    </script>
@endpush
