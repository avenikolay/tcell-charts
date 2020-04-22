<template>
    <div class="card" style="width: 18rem;">
        <div class="card-body">
            <h5 class="card-title">Загрузить</h5>
            <p class="card-text">Выберите файл в формате <strong>.xlsx</strong> и загрузите.</p>

            <div class="input-group mb-3">
                <div class="custom-file">
                    <input @change="upload($event)" type="file" ref="fileInput" class="custom-file-input" id="uploadExcelFile">
                    <label class="custom-file-label" for="uploadExcelFile" aria-describedby="uploadExcelFile">выбрать файл</label>
                </div>
            </div>

            <div class="progress" v-if="!complete">
                <div class="progress-bar" role="progressbar" :style="{ width: percentCompleted + '%' }" :aria-valuenow="percentCompleted" aria-valuemin="0" aria-valuemax="100">{{ percentCompleted }} %</div>
            </div>
            <p v-else class="pb-0 mb-0" :class="{'text-danger': error === true, 'text-success' : error === false }">{{ message }}</p>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'Upload',
        data() {
            return {
                percentCompleted: 0,
                complete: false,
                error: false,
                message: ''
            }
        },
        methods: {
            setResponse(error = false, message) {
                if (error){
                    this.error = true
                    this.message = message || 'Произошла ошибка.'
                } else {
                    this.message = 'Загружено!'
                }
                this.complete = true
                setTimeout(() => {
                    this.complete = false
                    this.error = false
                    this.message = ''
                }, 1000)
                this.percentCompleted = 0
                this.$refs.fileInput.value = ''
            },
            upload(event) {
                const file = event.target.files[0];

                if (file.type !== 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                    this.setResponse(true, 'Неверный тип файла.');
                    return;
                }

                let vm = this;
                const config = {
                    onUploadProgress (progressEvent) {
                        vm.percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    }
                }
                const formData = new FormData();
                formData.append('report', file);

                axios.post('/api/upload', formData, config)
                    .then(res =>  {
                        this.setResponse(false);
                        this.$emit('uploaded')
                    })
                    .catch(err => {
                        this.setResponse(true);
                    })
            }
        }
    }
</script>
