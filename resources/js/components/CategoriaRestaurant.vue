<template>
    <div class="container my-5">
        <h2>Restaurants</h2>

        <div class="row">
            
            <div class="col-md-4 mt-4" v-for="restaurant in this.restaurants" v-bind:key="restaurant.id">

                <div class="card shadow">
                    <img class="card-img-top" :src="`storage/${restaurant.imagen_principal}`" alt="Imagen Restaurant">
                    <div class="card-body">
                        <h3 class="card-title text-primary font-weight-bold"> {{ restaurant.nombre }} </h3>
                        <p class="card-text"> {{restaurant.direccion}} </p>
                        <p class="card-text"> 
                            <span class="font-weight-bold">Horarios</span>
                            {{restaurant.apertura}} - {{restaurant.cierre}} 
                        </p>

                        <router-link :to="{ name: 'establecimiento', params: { id: restaurant.id }}">
                            <a class="btn btn-primary d-block">Ver Lugar</a>
                        </router-link>

                    </div>
                </div>

            </div>

        </div>
    </div>

</template>

<script>
export default {
    mounted() {
        axios.get('/api/categorias/restaurant')
            .then(respuesta => {
                this.$store.commit("AGREGAR_RESTAURANTS", respuesta.data);
            });
    },
    computed: {
        restaurants() {
            return this.$store.state.restaurants;
        }
    }
}
</script>