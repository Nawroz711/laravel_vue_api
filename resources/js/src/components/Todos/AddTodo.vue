<template>
  <div v-if="message" class="alert alert-success alert-dismissible fade show" role="alert">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    {{ message }}
  </div>

  <Navbar />
  <div class="container">
    <div class="row mt-3">
      <div class="col-lg-6 mx-auto d-block bg-light p-4">
        <h4>Add todo</h4>

        <form @submit.prevent="submitForm" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="" class="form-label">Title</label>
            <input type="text" v-model="form_data.title" class="form-control" />
            <small class="text-danger" v-if="errors?.title">{{ errors.title[0] }}</small>
          </div>
          <div class="mb-3">
            <label for="" class="form-label">Description</label>
            <textarea v-model="form_data.description" class="form-control" cols="30" rows="10"></textarea>
            <small class="text-danger" v-if="errors?.description">{{ errors.description[0] }}</small>
          </div>
          <div class="mb-3">
            <div class="mb-3">
              <label for="" class="form-label">Priority</label>
              <select v-model="form_data.priority" class="form-select form-select-lg">
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

          <button class="btn btn-primary px-5 my-4" type="submit">Add</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import Navbar from '../../utils/Navbar.vue';
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const errors = ref()
const message = ref()
const auth_token = ref(localStorage.getItem('auth_token'))
const router = useRouter()


const form_data = ref({
  title: '',
  description: '',
  attachments: '',
  priority: ''
});

function selectFile(e) {
  form_data.value.attachments = e.target.files[0];
}

const submitForm = async () => {
  const config = {
    headers: {
      'content-type': 'multipart/form-data',
      Authorization: 'Bearer ' + auth_token.value
    }
  };

  const data = new FormData();
  data.append('title', form_data.value.title);
  data.append('description', form_data.value.description);
  data.append('priority', form_data.value.priority);
  data.append('attachments', form_data.value.attachments);

  try {
    await axios.post('api/todo/create', data, config)
      .then((res) => {
        if (res.status == 200) {
          message.value = res.data.message
        }

      })
  } catch (e) {
    errors.value = e.response.data.errors
  }
  finally {
    router.push({name: 'Todos'})
  }
};
</script>

<style scoped></style>