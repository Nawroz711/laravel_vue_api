<template>
    <Navbar />
    <div class="container">
        <div class="row mt-3">
            <div class="col-lg-6 mx-auto d-block bg-light p-4">
                <h4>Add todo</h4>

                <form action="" method="post" class="mt-5" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="" class="form-label">Title</label>
                        <input type="text" name="" id="" class="form-control" placeholder=""
                            aria-describedby="helpId" />
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Description</label>
                        <textarea class="form-control" name="" id="" cols="30" rows="10"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="mb-3">
                            <label for="" class="form-label">Priority</label>
                            <select class="form-select form-select-lg" name="" id="">
                                <option value="">High</option>
                                <option value="">Medium</option>
                                <option value="">Low</option>
                            </select>
                        </div>

                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Attachment</label>
                        <input @change="selectFile" type="file" name="" id="" class="form-control" placeholder=""
                            aria-describedby="helpId" />
                    </div>

                <button class="btn btn-primary px-5 my-4" type="submit">Add</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import Navbar from '../../utils/Navbar.vue';
import { onUpdated, ref } from 'vue';


let file = null;
function selectFile(e) {
    file = e.target.files[0];

}


const form_data = ref({
    'title' : '',
    'description' : '',
    'attachments' : '',
    'priority' : '',
})

</script>

<style scoped></style>