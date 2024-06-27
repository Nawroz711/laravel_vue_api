<template>
    <h1>Login page</h1>

    <form @submit.prevent="submitForm()">
        <div class="mb-3">
            <label for="" class="form-label">Email</label>
            <input type="text" name="" v-model="form_data.email" id="" class="form-control" placeholder="" aria-describedby="helpId" />
            <br> <br>
            <label for="" class="form-label">password</label>
            <input type="text" v-model="form_data.password" name="" id="" class="form-control" placeholder="" aria-describedby="helpId" />
        </div>
        <button type="submit">login</button>
    </form>
    <router-link :to="{name: 'Signup'}">create an account</router-link>
</template>

<script setup>
import axios from 'axios';
import {ref} from 'vue';

const form_data = ref({
    email : '',
    password : '',
})

const errors = ref()

const submitForm  = async () => {
    try{
        await axios.post('api/signin' , form_data.value)
        .then(res => {
            console.log(res.data);
            localStorage.setItem('auth_token' , res.data.token)
        })
    }
    catch(e)
    {
        console.log(e.response.data.errors);
        errors.value = e.response.data.errors;
        
        
    }
}

</script>