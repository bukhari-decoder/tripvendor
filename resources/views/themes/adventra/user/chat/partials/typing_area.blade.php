<form action="{{ route('user.chat.reply', $chat->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <input type="hidden" name="chat" value="{{ $chat->id ?? null }}" id="chatId">
    <input type="hidden" name="product_id" value="{{ $chat->package_id }}" id="productId">

    <div class="chat-box-bottom">
        <div class="chat-box-bottom-inner">
            <div class="row d-none" id="imagePreviewRow">
                <div id="imagePreview" class="message-image-preview"></div>
            </div>
            <div class="chat-message-box">
                <div class="cmn-btn-group2 d-flex justify-content-sm-end align-items-center">
                    <input type="file" name="attachments[]" accept="image/*" id="attachment"
                           style="display: none;" multiple onchange="previewTicketImage(event)">
                    <button type="button" data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-title="Image File" class="single-btn2" id="exportImageButton"
                            onclick="document.getElementById('attachment').click();">
                        <i class="fa-light fa-image"></i>
                    </button>

                    <button type="button" data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-title="Send Emoji" class="single-btn2" id="emojiButton">
                        <i class="fa-light fa-face-smile"></i>
                    </button>
                </div>
                <textarea class="form-control" id="messageBox" name="message" rows="3"></textarea>
                <button type="submit" data-bs-placement="top" name="replayChat" value="0"
                        class="message-send-btn">
                    <i class="fa-thin fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
    <emoji-picker id="emojiPicker"></emoji-picker>
</form>
@push('style')
    <style>
        .img-wrapper {
            position: relative;
            display: inline-block;
            margin: 5px;
        }
        .preview-image {
            display: block;
        }
        .remove-preview {
            position: absolute;
            top: -5px;
            right: -5px;
            background: red;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            padding: 0 5px;
        }
        #emojiPicker {
            position: absolute;
            bottom: 70px;
            left: 10px;
            display: none;
        }
    </style>

@endpush
@push('script')
    <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function previewTicketImage(event) {
                let imagePreviewRow = document.getElementById('imagePreviewRow');
                let imagePreview = document.getElementById('imagePreview');
                imagePreview.innerHTML = '';

                let files = event.target.files;
                if (files.length > 0) {
                    imagePreviewRow.classList.remove('d-none');
                } else {
                    imagePreviewRow.classList.add('d-none');
                }

                for (let i = 0; i < files.length; i++) {
                    let file = files[i];
                    if (file.size > 2 * 1024 * 1024) {
                        alert('File size exceeds 2 MB');
                        continue;
                    }
                    if (!file.type.startsWith('image/')) {
                        alert('Only image files are allowed');
                        continue;
                    }
                    let reader = new FileReader();

                    reader.onload = function (e) {
                        let imgWrapper = document.createElement('div');
                        imgWrapper.classList.add('img-wrapper');

                        let img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxWidth = '100px';
                        img.style.maxHeight = '100px';
                        img.style.borderRadius = '10px';
                        img.classList.add('preview-image');

                        let removeButton = document.createElement('span');
                        removeButton.innerHTML = '&times;';
                        removeButton.classList.add('remove-preview');
                        removeButton.onclick = function () {
                            imagePreview.removeChild(imgWrapper);
                            if (imagePreview.children.length === 0) {
                                imagePreviewRow.classList.add('d-none');
                            }
                        };

                        imgWrapper.appendChild(img);
                        imgWrapper.appendChild(removeButton);
                        imagePreview.appendChild(imgWrapper);
                    };

                    reader.readAsDataURL(file);
                }
            }

            document.getElementById('attachment').addEventListener('change', previewTicketImage);

            const emojiButton = document.getElementById('emojiButton');
            const emojiPicker = document.getElementById('emojiPicker');

            emojiButton.addEventListener('click', function() {
                if (emojiPicker.style.display === 'none' || emojiPicker.style.display === '') {
                    emojiPicker.style.display = 'block';
                } else {
                    emojiPicker.style.display = 'none';
                }
            });

            document.addEventListener('click', function(event) {
                if (!emojiPicker.contains(event.target) && event.target !== emojiButton) {
                    emojiPicker.style.display = 'none';
                }
            });

            emojiPicker.addEventListener('emoji-click', function(event) {
                const messageBox = document.getElementById('messageBox');
                messageBox.value += event.detail.unicode;
                emojiPicker.style.display = 'none';
            });
        });

    </script>
@endpush
