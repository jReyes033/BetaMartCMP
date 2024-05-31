<template>
  <div class="UserList">
    <h2>User List</h2>
    <button class="btn btn-primary add-user-button" @click="showModal">Add User</button>
    <div class="table-responsive">
      <table class="table table-striped">
        <thead class="thead-dark">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Password</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="account in accounts" :key="account.id">
            <td>{{ account.id }}</td>
            <td>{{ account.name }}</td>
            <td>{{ account.email }}</td>
            <td>{{ account.password }}</td>
            <td>
              <button @click="navigateToEdit(account.id)">Edit</button>
              <button @click="confirmDeleteUser(account.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Modal for Adding User -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitForm">
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" v-model="newUser.name" required>
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" v-model="newUser.email" required>
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" v-model="newUser.password" required>
              </div>
              <button type="submit" class="btn btn-primary">Add User</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Swal from 'sweetalert2';

export default {
  name: 'UserList',
  data() {
    return {
      accounts: [],
      newUser: {
        name: '',
        email: '',
        password: ''
      }
    };
  },
  mounted() {
    this.fetchAccounts();
  },
  methods: {
    fetchAccounts() {
      fetch('http://127.0.0.1:8000/api/account', {
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
          if (data.status === 200) {
            this.accounts = data.accounts; // Ensure the correct field name
          } else {
            console.error('No records found');
          }
        })
        .catch(error => {
          console.error('Error fetching accounts:', error);
        });
    },
    navigateToEdit(accountId) {
      this.$router.push(`/edit-account/${accountId}`);
    },
    confirmDeleteUser(accountId) {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then(result => {
        if (result.isConfirmed) {
          this.deleteAccount(accountId);
        }
      });
    },
    deleteAccount(accountId) {
      fetch(`http://127.0.0.1:8000/api/account/${accountId}/delete`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
        },
      })
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          this.accounts = this.accounts.filter(account => account.id !== accountId);
          Swal.fire('Deleted!', 'Account has been deleted.', 'success');
        })
        .catch(error => {
          console.error('Error deleting account:', error);
          Swal.fire('Error!', 'Failed to delete account.', 'error');
        });
    },
    showModal() {
      $('#addUserModal').modal('show');
    },
    submitForm() {
      fetch('http://127.0.0.1:8000/api/account', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(this.newUser)
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        if (data.status === 201) {
          // Assuming 'accounts' is an array in your Vue component to store user data
          this.accounts.push(data.account);
          // Clear the form fields
          this.newUser.name = '';
          this.newUser.email = '';
          this.newUser.password = '';
          // Close the modal (if you're using one)
          // $('#addUserModal').modal('hide');
          console.log('User added successfully:', data.account);
        } else {
          console.error('Failed to add account:', data.message);
        }
      })
      .catch(error => {
        console.error('Error adding account:', error);
      });
    }
  }
};
</script>