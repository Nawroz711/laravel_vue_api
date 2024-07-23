<template>
    <div class="container">
        <div class="row">
            <form @submit.prevent="submitForm()">
                <div class="col-lg-4 bg-light p-4 shadow rounded">
                    <h3 class="py-3">Login into your account!</h3>
                    <span class="text-danger">{{ error_message }}</span>
                    <div class="mb-3">
                        <label for="" class="form-label">Email</label>
                        <input type="text" name="" v-model="form_data.email" id="" class="form-control" placeholder=""
                            aria-describedby="helpId" />

                        <label for="" class="form-label">password</label>
                        <input type="password" v-model="form_data.password" name="" id="" class="form-control"
                            placeholder="" aria-describedby="helpId" />
                        <button class="btn btn-primary mt-5 w-100" type="submit">login</button>
                        <button class="btn btn-danger mt-2 w-100" type="submit">login with Google</button>
                    </div>

                    <router-link :to="{ name: 'Signup' }">create an account</router-link>
                </div>
            </form>
        </div>
    </div>

</template>

<script setup>
import axios from 'axios';
import { onUpdated, ref } from 'vue';
import { useRouter } from 'vue-router';

const form_data = ref({
    email: '',
    password: '',
})

const router = useRouter()

const errors = ref()
let error_message = ref()
const submitForm = async () => {
    error_message.value = ''
    try {
        await axios.post('api/signin', form_data.value)
            .then(res => {
                localStorage.setItem('auth_token', res.data.token)
                router.push({name: 'Todos'});
            })
    }
    catch (e) {
        console.log(e);
        if (e.response.status === 404) {
            error_message.value = e.response.data.message
        }
        errors.value = e.response.data.errors;
    }
}


</script>

<style scoped>
.col-lg-4 {
    display: block;
    margin: 0px auto;
}

form {
    padding-top: 20vh;
}
</style>