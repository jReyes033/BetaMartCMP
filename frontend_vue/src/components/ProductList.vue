<template>
    <div class="ProductList">
      <h2>Product List</h2>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead class="thead-dark">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Description</th>
              <th>Price</th>
              <th>Quantity</th>
              <!-- Add more table headers as needed -->
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in products.products" :key="product.id">
              <td>{{ product.id }}</td>
              <td>{{ product.name }}</td>
              <td>{{ product.description }}</td>
              <td>{{ product.price }}</td>
              <td>{{ product.quantity }}</td>
              <!-- Add more table cells for other product properties -->
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </template>
  

<script>
export default {
    name: 'ProductList',
    data() {
        return {
            products: []
        };
    },
    mounted() {
        this.fetchProducts();
    },
    methods: {
        fetchProducts() {
            // Make a GET request to retrieve the list of products
            fetch('http://127.0.0.1:8000/api/products', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json', // adjust the content type if needed
                    // You may need to include other headers here, such as authorization headers
                },
                // You can include other options here, such as query parameters
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    this.products = data; // Assuming data is an array of products
                    this.dumpData();
                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                });
        },
        dumpData(){
            console.log(this.products.products);
        }
    }
}
</script>