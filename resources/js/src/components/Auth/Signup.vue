<template>
    <div class="container">
        <div class="row">
            <form @submit.prevent="submitForm()">
            <div class="col-lg-4 bg-light p-4 shadow rounded">
                <h3 class="py-3">Signup page</h3>
                    <div class="mb-3">
                        <label for="" class="form-label">Name</label>
                        <input type="text" name="" v-model="form_data.name" id="" class="form-control" placeholder="" aria-describedby="helpId" />
                        <small v-if="errors?.name">{{ errors.name[0] }}</small> <br>
                        
                        <label for="" class="form-label">Email</label>
                        <input type="text" name="" v-model="form_data.email" id="" class="form-control" placeholder="" aria-describedby="helpId" />
                        <small v-if="errors?.email">{{ errors.email[0] }}</small> <br>
            
                        <label for="" class="form-label">password</label>
                        <input type="text" v-model="form_data.password" name="" id="" class="form-control" placeholder="" aria-describedby="helpId" /> 
                        <small v-if="errors?.password">{{ errors.password[0] }}</small>
            
                    </div>
                    <button class="btn btn-primary my-3" type="submit">signup</button> <br>
                    
                    <router-link :to="{name: 'Login'}">already have an account</router-link>
                </div>
            </form>
        </div>
    </div>


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

<style scoped>
.col-lg-4 {
    display: block;
    margin: 0px auto;
}
form {
    padding-top: 20vh;
}
small {
    color: red;
}

</style>