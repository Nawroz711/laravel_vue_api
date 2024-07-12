<template>
    <div class="">
        <div class="">
            <div class="d-flex justify-content-between p-4" v-for="x in 6">
                <Skeleton height="2.5rem" class="mx-2"></Skeleton>
                <Skeleton height="2.5rem" class="mx-2"></Skeleton>
                <Skeleton height="2.5rem" class="mx-2"></Skeleton>
                <Skeleton height="2.5rem" class="mx-2"></Skeleton>
                <Skeleton height="2.5rem" class="mx-2"></Skeleton>
            </div>
        </div>
    </div>

</template>

<script setup>
import Skeleton from 'primevue/skeleton';

</script>