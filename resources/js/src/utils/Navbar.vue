<template>
    <nav class="navbar navbar-expand-sm navbar-dark bg-secondary">
        <div class="container">
            <a class="navbar-brand" href="#">Dashboard</a>
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavId">
                <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                    <li class="nav-item">
                        <router-link class="nav-link"  :to="{name: 'Todos'}">
                            <span>Todos</span>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link"  :to="{name: 'AddTodo'}">
                            <span>New Todo</span>
                        </router-link>
                    </li>
                </ul>
                <form @submit.prevent="logout()" class="d-flex my-2 my-lg-0">
                    <button class="btn btn-outline-light my-2 my-sm-0" type="submit">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

</template>

<script setup>
import axios from 'axios';
import { ref } from 'vue';
import { useRouter } from 'vue-router';


const auth_token = ref(localStorage.getItem('auth_token'))

const router = useRouter();
const logout = async () => {
    try {
        await axios.post('api/logout' , {token: auth_token.value} , {
            headers: {
                Authorization : 'Bearer ' + auth_token.value
            }
        })
            .then(res => {
                localStorage.removeItem('auth_token');
                router.push({name: 'Login'});
            })
    }
    catch (e) {
        console.log(e);
    }
}

</script>