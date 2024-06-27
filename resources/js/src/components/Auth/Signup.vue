<template>
    <h1>Signup page</h1>


    <div v-if="errors">
    </div>
    <form @submit.prevent="submitForm()">
        <div class="mb-3">
            <label for="" class="form-label">Name</label>
            <input type="text" name="" v-model="form_data.name" id="" class="form-control" placeholder="" aria-describedby="helpId" />
            <small v-if="errors?.name">{{ errors.name[0] }}</small>
            <br> <br>
            <label for="" class="form-label">Email</label>
            <input type="text" name="" v-model="form_data.email" id="" class="form-control" placeholder="" aria-describedby="helpId" />
            <small v-if="errors?.email">{{ errors.email[0] }}</small>

            <br> <br>
            <label for="" class="form-label">password</label>
            <input type="text" v-model="form_data.password" name="" id="" class="form-control" placeholder="" aria-describedby="helpId" />
            <small v-if="errors?.password">{{ errors.password[0] }}</small>

            <br><br>
        </div>

        <button type="submit">signup</button>
    </form>
    <router-link :to="{name: 'Login'}">already have an account</router-link>
</template>

<script setup>
import axios from 'axios';
import {ref} from 'vue';

const form_data = ref({
    name : '',
    email : '',
    password : '',
})

const errors = ref()

const submitForm  = async () => {
    try{
        await axios.post('api/signup' , form_data.value)
        .then(res => {
            console.log(res.data);
        })
    }
    catch(e)
    {
        console.log(e.response.data.errors);
        errors.value = e.response.data.errors;
        
        
    }
}

</script>