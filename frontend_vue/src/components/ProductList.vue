<template>
  <div class="ProductList">
    <h2>Product List</h2>
    <button class="btn btn-primary add-product-button" @click="addUser">Add Product</button>
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
              <button class="btn btn-danger btn-sm" @click="deleteProduct(product)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Edit Product Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
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
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import $ from 'jquery';

export default {
    name: 'ProductList',
    data() {
        return {
            products: [],
            editProductData: {
                id: null,
                name: '',
                description: '',
                price: null
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
            console.log('View product', product);
        },
        editProduct(product) {
            this.editProductData = { ...product };
            $('#editModal').modal('show');
        },
        saveProduct() {
            const index = this.products.products.findIndex(p => p.id === this.editProductData.id);
            if (index !== -1) {
                this.$set(this.products.products, index, { ...this.editProductData });
            }
            $('#editModal').modal('hide');
        },
        deleteProduct(product) {
            console.log('Delete product', product);
        },
        increaseQuantity(product) {
            product.quantity++;
        },
        decreaseQuantity(product) {
            if (product.quantity > 0) {
                product.quantity--;
            }
        },
        addUser() {
            console.log('Add user clicked');
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
}

</style>