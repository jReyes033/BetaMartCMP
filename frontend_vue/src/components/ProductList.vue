<template>
  <div class="ProductList">
    <h2>Product List</h2>
    <div class="table-responsive">
      <table class="table table-striped table-bordered">
        <thead class="thead-dark">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in products.products" :key="product.id">
            <td>{{ product.id }}</td>
            <td>{{ product.name }}</td>
            <td>{{ product.description }}</td>
            <td>{{ product.price }}</td>
            <td>
              <button class="btn btn-secondary btn-sm" @click="decreaseQuantity(product)">-</button>
              {{ product.quantity }}
              <button class="btn btn-secondary btn-sm" @click="increaseQuantity(product)">+</button>
            </td>
            <td>
              <button class="btn btn-info btn-sm" @click="viewProduct(product)">View</button>
              <button class="btn btn-warning btn-sm" @click="editProduct(product)">Update</button>
              <button class="btn btn-danger btn-sm" @click="confirmDelete(product)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <Modal v-if="showEditModal" @close="showEditModal = false">
      <template v-slot:header>
        <h5>Edit Product Details</h5>
      </template>
      <template v-slot:body>
        <form @submit.prevent="saveProduct">
          <div class="form-group">
            <label for="productName">Name</label>
            <input type="text" class="form-control" id="productName" v-model="editProductData.name">
          </div>
          <div class="form-group">
            <label for="productDescription">Description</label>
            <input type="text" class="form-control" id="productDescription" v-model="editProductData.description">
          </div>
          <div class="form-group">
            <label for="productPrice">Price</label>
            <input type="number" class="form-control" id="productPrice" v-model="editProductData.price">
          </div>
          <button type="submit" class="btn btn-primary">Save</button>
        </form>
      </template>
    </Modal>

    <ViewModal v-if="showViewModal" :product="viewProductData" @close="showViewModal = false" />

  </div>
</template>

<script>
import Modal from './EditModal.vue';
import ViewModal from './ViewModal.vue';

export default {
  name: 'ProductList',
  components: {
    Modal,
    ViewModal
  },
  data() {
    return {
      products: [],
      showEditModal: false,
      showViewModal: false,
      editProductData: {
        id: null,
        name: '',
        description: '',
        price: null
      },
      viewProductData: {
        id: null,
        name: '',
        description: '',
        price: null,
        quantity: null
      }
    };
  },
  mounted() {
    this.fetchProducts();
  },
  methods: {
    fetchProducts() {
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
          this.products = data;
        })
        .catch(error => {
          console.error('Error fetching products:', error);
        });
    },
    viewProduct(product) {
      this.viewProductData = { ...product };
      this.showViewModal = true;
    },
    editProduct(product) {
      this.editProductData = { ...product };
      this.showEditModal = true;
    },
    saveProduct() {
      const index = this.products.products.findIndex(p => p.id === this.editProductData.id);
      if (index !== -1) {
        this.products.products.splice(index, 1, { ...this.editProductData });
      }
      this.showEditModal = false;
    },
    confirmDelete(product) {
      const confirmMessage = `Are you sure you want to delete product id: ${product.id} product name: ${product.name} product description: ${product.description} price: ${product.price}?`;
      if (window.confirm(confirmMessage)) {
        this.deleteProduct(product);
      }
    },
    deleteProduct(product) {
      const index = this.products.products.findIndex(p => p.id === product.id);
      if (index !== -1) {
        this.products.products.splice(index, 1);
      }
    },
    increaseQuantity(product) {
      product.quantity++;
    },
    decreaseQuantity(product) {
      if (product.quantity > 0) {
        product.quantity--;
      }
    }
  }
}
</script>

<style>
.ProductList {
  padding: 20px;
}

.ProductList h2 {
  text-align: center;
  margin-bottom: 20px;
}

.table-responsive {
  margin: auto;
  max-width: 80%;
}

.table {
  border-collapse: collapse;
}

.table thead th, .table tbody td {
  text-align: center;
  vertical-align: middle;
  border: 1px solid black; /* Adding black grid lines */
}

.table tbody tr:hover {
  background-color: #f5f5f5;
}

.btn-info {
  color: white;
  background-color: #17a2b8;
  border-color: #17a2b8;
}

.btn-warning {
  color: white;
  background-color: #ffc107;
  border-color: #ffc107;
}

.btn-danger {
  color: white;
  background-color: #dc3545;
  border-color: #dc3545;
}

.btn-secondary {
  color: white;
  background-color: #6c757d;
  border-color: #6c757d;
}

.btn-sm {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
  line-height: 1.5;
  border-radius: 0.2rem;
}
</style>
