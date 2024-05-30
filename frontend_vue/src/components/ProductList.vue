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
              <th>Action</th>
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
              <td>
                <button @click="editProduct(product)">Edit</button>
                <button @click="deleteProduct(product.id)">Delete</button>
              </td>
              <!-- Add more table cells for other product properties -->
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </template>
  
  <script>
import Swal from 'sweetalert2';

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
          'Content-Type': 'application/json',
        },
      })
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          this.products = data; // Assuming data is an array of products
        })
        .catch(error => {
          console.error('Error fetching products:', error);
        });
    },
    editProduct(product) {
      // Redirect to the edit page
      this.$router.push({ name: 'EditProduct', params: { id: product.id } });
    },
    confirmDeleteProduct(productId) {
      // Show a confirmation dialog using SweetAlert2
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          this.deleteProduct(productId);
        }
      });
    },
    deleteProduct(productId) {
      // Make a DELETE request to delete the product
      fetch(`http://127.0.0.1:8000/api/products/${productId}/delete`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
        },
      })
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          // Remove the product from the local list after successful deletion
          this.products.products = this.products.products.filter(product => product.id !== productId);

          // Show a success notification using SweetAlert2
          Swal.fire(
            'Deleted!',
            'Your product has been deleted.',
            'success'
          );
        })
        .catch(error => {
          console.error('Error deleting product:', error);

          // Show an error notification using SweetAlert2
          Swal.fire(
            'Error!',
            'There was an error deleting the product.',
            'error'
          );
        });
    }
  }
}
</script>