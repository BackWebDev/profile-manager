<template>
    <b-container class="text-center">
        <h1>Edit profile</h1>
        <b-form @submit="onSubmit">
            <b-form-group id="input-group-2" label="Your Name:" label-for="input-2">
                <b-form-input
                    id="input-2"
                    v-model="name"
                    required
                    placeholder="Enter name"
                ></b-form-input>
            </b-form-group>
            <div class="current-images-preview" v-show="currentImages.length">
                <div class="img-wrapper" v-for="(image, index) in currentImages" :key="index">
                    <img :src="image.url" alt="img">
                    <div class="non-flex" @click="deleteCurrentImage(image)">
                        Delete
                        <b-icon icon="trash-fill" aria-hidden="true"></b-icon>
                    </div>
                </div>
            </div>
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
        </b-form>
            <div class="message">
                {{ message }}
            </div>
    </b-container>
</template>

<script>
    import axios from 'axios';

    export default {
        name: 'Profile',
        data() {
            return {
                isDragging: false,
                dragCount: 0,
                files: [],
                name: '',
                message: '',
                images: [],
                currentImages: [],
                deletedCurrentImages: []
            }
        },
        created() {
            axios.get('/api/profiles/edit/' + this.$route.params.id)
            .then((response) => {
                this.name = response.data.profiles.name;
                this.currentImages = response.data.profiles.images;
            })
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
                formData.append('profile_id', this.$route.params.id);
                this.files.forEach(file => {
                    formData.append('images[]', file, file.name);
                });
                this.deletedCurrentImages.forEach(image => {
                    formData.append('deleted_images[]', image);
                });
                axios.post('/api/profiles/edit/update-profile', formData)
                    .then(response => {
                        this.$router.push('/');
                    })
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
                console.log(this.files);
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
            },
            deleteCurrentImage(image) {
                this.deletedCurrentImages.push(image.id);
                let index = this.currentImages.indexOf(image);
                this.currentImages.splice(index, 1);
            }
        }
    }
</script>

<style scoped>
    .current-images-preview {
        display: flex;
    }
    .current-images-preview img{
        width: 75px;
        height: 75px;
    }
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
</style>