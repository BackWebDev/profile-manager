<template>
    <b-container class="text-center">
        <h1>Create profile</h1>
        <b-form @submit="onSubmit" @reset="onReset">
            <b-form-group id="input-group-2" label="Your Name:" label-for="input-2">
                <b-form-input
                    id="input-2"
                    v-model="name"
                    required
                    placeholder="Enter name"
                ></b-form-input>
            </b-form-group>
            <div class="uploader"
                @dragenter="OnDragEnter"
                @dragleave="OnDragLeave"
                @dragover.prevent
                @drop="onDrop"
                :class="{ dragging: isDragging }">

                <div class="upload-control" v-show="images.length">
                    <label for="file">Select a file</label>
                </div>

                <div v-show="!images.length">
                    <i class="fa fa-cloud-upload"></i>
                    <p>Drag your images here</p>
                    <div>OR</div>
                    <div class="file-input">
                        <label for="file">Select a file</label>
                        <input type="file" id="file" @change="onInputChange" multiple>
                    </div>
                </div>
                <div class="images-preview" v-show="images.length">
                    <div class="img-wrapper" v-for="(image, index) in images" :key="index">
                        <img :src="image" :alt="`Image Uplaoder ${index}`">
                        <div class="details">
                            <span class="name" v-text="files[index].name"></span>
                            <span class="size" v-text="getFileSize(files[index].size)"></span>
                            <div class="non-flex" @click="deleteImage(image)">
                                Delete
                                <b-icon icon="trash-fill" aria-hidden="true"></b-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="message">
                {{ message }}
            </div>
            <b-button type="submit" variant="primary">Submit</b-button>
            <b-button type="reset" variant="danger">Reset</b-button>
        </b-form>
    </b-container>
</template>

<script>
  import axios from 'axios';

  export default {
    name: 'NewProfile',
    data() {
        return {
            isDragging: false,
            dragCount: 0,
            files: [],
            name: '',
            message: '',
            images: []
        }
    },
    methods: {
        onSubmit(evt) {
            evt.preventDefault()
            const formData = new FormData();
            if(/\d/.test(this.name)){
                this.message = 'The name can contain only text';
                return;
            }
            this.message = '';
            formData.append('name', this.name);
            this.files.forEach(file => {
                formData.append('images[]', file, file.name);
            });
            axios.post('api/profiles/new', formData)
                .then(response => {
                    this.$router.push('/');
                })
        },
        onReset(evt) {
            evt.preventDefault()
            // Reset our form values
            this.name = '';
            this.images = [];
            this.files = [];
        },
        onFileChange(event) {
            for(var key in event.target.files){
                this.postFormData.append('images[]', event.target.files[key]);
            }
            console.log(this.postFormData);
        },
        OnDragEnter(e) {
            e.preventDefault();
            
            this.dragCount++;
            this.isDragging = true;
            return false;
        },
        OnDragLeave(e) {
            e.preventDefault();
            this.dragCount--;
            if (this.dragCount <= 0)
                this.isDragging = false;
        },
        onInputChange(e) {
            const files = e.target.files;
            Array.from(files).forEach(file => this.addImage(file));
        },
        onDrop(e) {
            e.preventDefault();
            e.stopPropagation();
            this.isDragging = false;
            const files = e.dataTransfer.files;
            Array.from(files).forEach(file => this.addImage(file));
        },
        addImage(file) {
            if (!file.type.match('image.*')) {
                this.message = 'File or files is not an image';
                return;
            }
            this.message = '';
            this.files.push(file);
            const img = new Image(),
                reader = new FileReader();
            reader.onload = (e) => this.images.push(e.target.result);
            reader.readAsDataURL(file);
        },
        getFileSize(size) {
            const fSExt = ['Bytes', 'KB', 'MB', 'GB'];
            let i = 0;
            
            while(size > 900) {
                size /= 1024;
                i++;
            }
            return `${(Math.round(size * 100) / 100)} ${fSExt[i]}`;
        },
        deleteImage(image) {
            let index = this.images.indexOf(image);
            this.images.splice(index, 1);
            this.files.splice(index, 1);
        }
    }
  }
</script>

<style>
    .non-flex {
        display: block;
        margin: 0 auto;
        cursor: pointer;
    }
    .b-icon {
        color: red;
    }
    .message {
        color: red;
    }
    .uploader {
        width: 100%;
        background: #2196f3;
        color: #fff;
        padding: 40px 15px;
        text-align: center;
        border-radius: 10px;
        border: 3px dashed #fff;
        font-size: 20px;
        position: relative;
    }
    .uploader.dragging {
        background: #fff;
        color: #2196f3;
        border: 3px dashed #2196f3;
    }
    .uploader.dragging .file-input label {
        background: #2196f3;
        color: #fff;
    }
    .uploader i {
        font-size: 85px;
    }
    .uploader .file-input {
        width: 200px;
        margin: auto;
        height: 68px;
        position: relative;
    }
    .uploader .file-input label, .uploader .file-input input {
        background: #fff;
        color: #2196f3;
        width: 100%;
        position: absolute;
        left: 0;
        top: 0;
        padding: 10px;
        border-radius: 4px;
        margin-top: 7px;
        cursor: pointer;
    }
    .uploader .file-input input {
        opacity: 0;
        z-index: -2;
    }
    .uploader .images-preview {
        display: flex;
        flex-wrap: wrap;
        margin-top: 20px;
    }
    .uploader .images-preview .img-wrapper {
        width: 160px;
        display: flex;
        flex-direction: column;
        margin: 10px;
        height: 150px;
        justify-content: space-between;
        background: #fff;
        box-shadow: 5px 5px 20px #3e3737;
    }
    .uploader .images-preview .img-wrapper img {
        max-height: 105px;
    }
    .uploader .images-preview .details {
        font-size: 12px;
        background: #fff;
        color: #000;
        display: flex;
        flex-direction: column;
        align-items: self-start;
        padding: 3px 6px;
    }
    .uploader .images-preview .details .name {
        overflow: hidden;
        height: 18px;
    }
    .uploader .upload-control {
        position: absolute;
        width: 100%;
        background: #fff;
        top: 0;
        left: 0;
        border-top-left-radius: 7px;
        border-top-right-radius: 7px;
        padding: 10px;
        padding-bottom: 4px;
        text-align: right;
    }
    .uploader .upload-control button, .uploader .upload-control label {
        background: #2196f3;
        border: 2px solid #03a9f4;
        border-radius: 3px;
        color: #fff;
        font-size: 15px;
        cursor: pointer;
    }
    .uploader .upload-control label {
        padding: 2px 5px;
        margin-right: 10px;
    }
</style>