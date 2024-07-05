<template>
    <Navbar />
    <DataTable v-if="data" ref="dt" class="p-4" stripedRows removableSort :value="data.data"
        tableStyle="min-width: 50rem">
        <template #header>
            <div style="text-align: left" class="d-flex justify-content-end">
                <button class="btn btn-primary btn-sm px-3" @click="exportCSV($event)">Export file</button>
                <router-link :to="{name: 'AddTodo'}">
                    <button class="btn btn-success btn-sm px-3 mx-2">Create new</button>
                </router-link>
            </div>
        </template>

        <Column field="id" header="ID" sortable style="width: 1%"></Column>
        <Column field="title" header="Title" sortable style="width: 15%"></Column>
        <Column field="description" header="Description" sortable style="width: 30%"></Column>
        <Column field="priority" header="Priority" sortable style="width: 5%">
            <template #body="{ data }">
                <Tag class="w-75 rounde-circle" :value="data.priority" :severity="getSeverity(data.priority)" />
            </template>
        </Column>
        <Column field="attachments" header="File" sortable style="width: 10%">
            <template #body="{ data }">
                <button @click.prevent="downloadFile(data.attachments)" v-if="data.attachments" class="btn btn-sm btn-success rounded px-3 mx-auto d-block">Download</button>
                <small v-else class="text-center d-block">No file!</small>
            </template>
        </Column>
        <Column style="width: 1%">
            <template #body="{ data }">
                <div class="dropdown open">
                    <a class="dropdown-toggle text-secondary" style="font-size: 1.6rem;" type="button" id="triggerId" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    </a>
                    <div class="dropdown-menu" aria-labelledby="triggerId">
                        <a class="dropdown-item" href="#">Edit</a>
                        <a class="dropdown-item" href="#">Delete</a>
                    </div>
                </div>

            </template>
        </Column>
        <template #footer>
            <Bootstrap5Pagination :data="data" @pagination-change-page="getData" />
        </template>
    </DataTable>


</template>

<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Tag from 'primevue/tag';
import Button from 'primevue/button';
import { onMounted, ref } from 'vue';
import Navbar from '../../utils/Navbar.vue';
import axios from 'axios';
import { Bootstrap5Pagination } from 'laravel-vue-pagination';


const dt = ref();
const products = ref();
const exportCSV = () => {
    dt.value.exportCSV();
};

const auth_token = ref(localStorage.getItem('auth_token'));
const data = ref();
const page_number = ref(1)

const getData = async (page = page_number.value) => {
    try {
        await axios.get(`api/todos?page=${page}`, {
            headers: {
                Authorization: 'Bearer ' + auth_token.value
            }
        }).then((res) => {
            console.log(res.data);
            data.value = res.data;
            page_number.value = page;
        })
    }
    catch (e) {
        console.log(e);
    }

}


const downloadFile = async (file) => {
    try {
        await axios.get(`api/download/file/${file}`, {
            headers: {
                Authorization: 'Bearer ' + auth_token.value
            }
        }).then((res) => {
            console.log(res.data);
        })
    }
    catch (e) {
        console.log(e);
    }
}

const getSeverity = (priority) => {
    switch (priority) {
        case 'High':
            return 'danger';

        case 'Medium':
            return 'warning';

        case 'Low':
            return 'success';

        case 'renewal':
            return null;
    }
}
onMounted(() => {
    getData();
})

</script>