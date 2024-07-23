<template>
    <div v-if="message" class="alert alert-success alert-dismissible fade show" role="alert">
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      {{ message }}
    </div>

    <Navbar />
    <div class="container">
        <div class="row mt-3">
            <div class="col-lg-6 mx-auto d-block bg-light p-4">
                <h4>Edit todo</h4>

                <form v-if="data" @submit.prevent="updateData" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="" class="form-label">Title</label>
                        <input type="text" v-model="data.title" class="form-control" />
                        <small class="text-danger" v-if="errors?.title">{{ errors.title[0] }}</small>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Description</label>
                        <textarea v-model="data.description" class="form-control" cols="30" rows="10"></textarea>
                        <small class="text-danger" v-if="errors?.description">{{ errors.description[0] }}</small>
                    </div>
                    <div class="mb-3">
                        <div class="mb-3">
                            <label for="" class="form-label">Priority</label>
                            <select v-model="data.priority" class="form-select form-select-lg">
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Attachment</label>
                        <input @change="selectFile" type="file" class="form-control" />
                    </div>

                    <button class="btn btn-primary px-5 my-4" type="submit">Update</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import Navbar from '../../utils/Navbar.vue';
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

const auth_token = ref(localStorage.getItem('auth_token'));
const data = ref()
const errors = ref();
const message = ref();

const getData = async () => {
    try {
        await axios.get(`/api/todos/edit/${route.params.id}`, {
            headers: {
                Authorization: 'Bearer ' + auth_token.value
            }
        }).then((res) => {
            console.log(res.data);
            data.value = res.data

        })
    }
    catch (e) {
        console.log(e);
    }
    finally {
        // loading.value = false;
        // data.value = null;
    }

}

function selectFile(e) {
  data.value.attachments = e.target.files[0];
}

const updateData = async () => {
    const config = {
        headers: {
            'content-type': 'multipart/form-data',
            Authorization: 'Bearer ' + auth_token.value
        }
    };

    const new_data = new FormData();
    new_data.append('id', data.value.id);
    new_data.append('title', data.value.title);
    new_data.append('description', data.value.description);
    new_data.append('priority', data.value.priority);
    new_data.append('attachments', data.value.attachments);


    try {
        await axios.post(`/api/todos/update/`, new_data, config ,{
        }).then((res) => {
            console.log(res.data);
            data.value = res.data
            if(res.status == 200)
            {
                message.value = res.data.message;
                setTimeout(() => {
                    router.push({name : 'Todos'})
                }, 1000);
            }

        })
    }
    catch (e) {
        console.log(e);
        errors.value = e.response.data.errors

    }
    finally {
        // loading.value = false;
        // data.value = null;
    }

}



onMounted(() => {
    getData();
})

</script>