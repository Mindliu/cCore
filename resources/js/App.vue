<template>
    <div id="app" class="h-100">
        <div class="container-fluid h-100">
            <div class="row h-100">
                <div class="col-md-8 offset-2 h-100 d-flex justify-content-center align-items-center my-5">
                    <List/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import List from './components/List.vue'

export default {
    name: 'App',
    components: {
        List
    },

    methods: {

    },

}
</script>


<style>
#app {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
}
.vue-bg {
    background: #bce5d0;
}
</style>
