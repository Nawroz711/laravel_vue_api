<template>
    <Navbar />
    <div v-if="data">
        <DataTable v-model:selection="selectedProduct" ref="dt"
            class="p-4" stripedRows removableSort :value="data.data" tableStyle="min-width: 50rem">

            <template #header>
                <div class="d-flex justify-content-between">
                    <form @submit.prevent="search()" class="search-header d-flex">
                        <input v-model="searchInput" type="text" placeholder="search..." class="rounded-0 form-control"
                            name="" id="">
                        <button class="btn btn-sm btn-primary rounded-0 px-4" type="submit">search</button>
                    </form>
                    <div style="text-align: left" class="">
                        <button class="btn btn-primary btn-sm px-3" @click="exportCSV($event)">Export file</button>
                        <router-link :to="{ name: 'AddTodo' }">
                            <button class="btn btn-success btn-sm px-3 mx-2">Create new</button>
                        </router-link>
                    </div>

                </div>

                <!-- delete option -->
                <div v-if="selectedProduct.length > 0" class="my-2 mx-2 d-flex justify-content-end">
                    <button @click.prevent="deleteRecords()" class="btn btn-sm btn-danger">Delete {{
                        selectedProduct.length }} records!</button>
                </div>

            </template>


            <Column selectionMode="multiple" style="width: 1%;"></Column>
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
                    <button @click.prevent="downloadFile(data.attachments)" v-if="data.attachments"
                        class="btn btn-sm btn-success rounded px-3 mx-auto d-block">Download</button>
                    <small v-else class="text-center d-block">No file!</small>
                </template>
            </Column>
            <Column style="width: 1%">
                <template #body="{ data }">
                    <small @click.prevent="edit = true">edit</small>
                </template>
            </Column>
            <template #footer>
                <Bootstrap5Pagination :data="data" @pagination-change-page="getData" />
            </template>
        </DataTable>
    </div>

    <div v-else class="">
        <div class="text-center my-5" v-if="error_message">
            <h1 class="">Oops!</h1>
            <p style="font-size: 10mm;" class="text-center my-4 d-block">{{ error_message }}</p>
            <button class="btn btn-sm btn-primary px-4" @click.prevent="getData">Back</button>
        </div>
        <div v-else>
            <Skeleton />
        </div>
    </div>


    <Toast />

</template>

<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Tag from 'primevue/tag';
import Button from 'primevue/button';
import { onMounted, ref, watch, watchEffect } from 'vue';
import Navbar from '../../utils/Navbar.vue';
import axios from 'axios';
import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import Skeleton from '../../utils/Skeleton.vue'
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

const toast = useToast();
const show = () => {
    toast.add({ severity: 'success', summary: 'Success', detail: message.value, life: 1000 });
};

const edit = ref(false)
const loading = ref(true)
const selectedProduct = ref([]);
const message = ref()

const searchInput = ref(null);

const dt = ref();
const products = ref();
const exportCSV = () => {
    dt.value.exportCSV();
};

const auth_token = ref(localStorage.getItem('auth_token'));
const data = ref();
const page_number = ref(1)

const getData = async (page = page_number.value) => {
    data.value = '';
    error_message.value = ''
    try {
        await axios.get(`api/todos/?page=${page}`, {
            headers: {
                Authorization: 'Bearer ' + auth_token.value
            }
        }).then((res) => {
            data.value = res.data;
            page_number.value = page;
        })
    }
    catch (e) {
        console.log(e);
    }
    finally {
        loading.value = false;
        // data.value = null;
    }

}


//SEARCH METHOD

const error_message = ref()
const search = async (page = page_number.value) => {
    data.value = '';
    error_message.value = ''

    try {
        await axios.post(`api/todos/search/?page=${page}`, { 'keyword': searchInput.value }, {
            headers: {
                Authorization: 'Bearer ' + auth_token.value
            }
        }).then((res) => {
            data.value = res.data;
        })
    }
    catch (e) {
        if (e.response.status == 404) {
            error_message.value = e.response.data.message;
        }
    }
    finally {
        loading.value = false
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

// DELETE METHOD

const deleteRecords = async () => {
    const config = {
        headers: {
            Authorization: 'Bearer ' + auth_token.value
        }
    };

    let uniqueArray = [...new Set(selectedProduct.value)];

    let delete_ids = [];
    uniqueArray.forEach((item) => {
        delete_ids.push(item['id'])
    })

    try {
        await axios.post('api/todos/delete', delete_ids, config)
            .then((res) => {
                console.log(res.status);
                if (res.status == 200) {
                    message.value = res.data.message;
                    show();
                    selectedProduct.value = ''

                }
            })
    }
    catch (e) {
        console.log(e);
    }
    finally {
        setTimeout(() => {
            getData()
        }, 1001)
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

const form_data = ref({
    'title' : '',
})

onMounted(() => {
    getData();
})

</script>

<style scoped>
.opacity {
    opacity: 30px !important;
    filter: opacity(40px) !important;
}

.progress-bar {
    position: absolute;
    top: 0;
    z-index: 100000;
}
</style>