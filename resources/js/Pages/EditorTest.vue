<template>
    <div class="container mx-auto py-8">
        <h1 class="mb-4 text-2xl font-bold">Testovací stránka editoru Hugerte</h1>

        <p class="mb-4 text-gray-600">
            Níže je jednoduchý editor Hugerte. Zkus do něj něco napsat a obsah se ti zobrazí i pod ním.
        </p>

        <div class="mb-6">
            <hugerte-editor v-model="editorContent" :init="editorConfig"></hugerte-editor>

            <div class="mt-4 text-sm">
                <div v-if="isUploading" class="mb-2 text-blue-600">
                    Nahrávám obrázek... {{ uploadProgress }} %
                </div>
                <div
                    v-if="!isUploading && uploadProgress > 0"
                    class="mb-2 text-green-600"
                >
                    Nahrávání dokončeno ({{ uploadProgress }} %)
                </div>
                <div v-if="uploadError" class="text-secondary-600">
                    Chyba nahrávání: {{ uploadError }}
                </div>
            </div>
        </div>

        <div class="mt-6 rounded border border-gray-200 bg-gray-50 p-4">
            <h2 class="mb-2 text-lg font-semibold">Aktuální obsah (HTML):</h2>
            <pre class="whitespace-pre-wrap break-words text-sm">{{ editorContent }}</pre>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import HugerteEditor from '@hugerte/hugerte-vue'

const editorContent = ref('<p>Ahoj z editoru Hugerte 👋</p>')

const isUploading = ref(false)
const uploadProgress = ref(0)
const uploadError = ref('')

const editorConfig = {
    height: 400,
    menubar: true,
    plugins: [
        'advlist',
        'autolink',
        'lists',
        'link',
        'image',
        'charmap',
        'print',
        'preview',
        'anchor',
        'searchreplace',
        'visualblocks',
        'code',
        'fullscreen',
        'insertdatetime',
        'media',
        'table',
        'code',
        'help',
        'wordcount'
    ],
    automatic_uploads: true,
    images_upload_handler: (blobInfo, progress) => {
        return new Promise((resolve, reject) => {
        isUploading.value = true
        uploadProgress.value = 0
        uploadError.value = ''

        const formData = new FormData()
        formData.append('file', blobInfo.blob(), blobInfo.filename())

        axios
            .post('/editor/image-upload-test', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: (event) => {
                    if (!event.total) {
                        return
                    }

                    const percent = Math.round((event.loaded * 100) / event.total)
                    uploadProgress.value = percent

                    if (typeof progress === 'function') {
                        progress(percent)
                    }
                }
            })
            .then((response) => {
                const imageUrl = response?.data?.location

                if (!imageUrl) {
                    throw new Error('Chybí URL obrázku v odpovědi serveru')
                }

                isUploading.value = false
                uploadProgress.value = 100
                resolve(imageUrl)
            })
            .catch((error) => {
                console.error(error)

                let message = 'Nahrání obrázku selhalo. Zkus to prosím znovu.'

                if (error.response && error.response.data) {
                    const data = error.response.data

                    if (data.message) {
                        message = data.message
                    }

                    if (data.errors && data.errors.file && data.errors.file.length > 0) {
                        message = data.errors.file[0]
                    }
                }

                uploadError.value = message
                isUploading.value = false
                uploadProgress.value = 0
                reject('Upload failed')
            })
        })
    },
    toolbar:
        'undo redo | formatselect | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help'
}
</script>


