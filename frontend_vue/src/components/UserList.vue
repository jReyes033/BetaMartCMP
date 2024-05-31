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
      <div class="modal-dialog modal-dialog-centered" role="document">
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

    <!-- Modal for Editing User -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitEditForm">
              <div class="form-group">
                <label for="edit-name">Name</label>
                <input type="text" class="form-control" id="edit-name" v-model="editUserForm.name" required>
              </div>
              <div class="form-group">
                <label for="edit-email">Email</label>
                <input type="email" class="form-control" id="edit-email" v-model="editUserForm.email" required>
              </div>
              <div class="form-group">
                <label for="edit-password">Password</label>
                <input type="password" class="form-control" id="edit-password" v-model="editUserForm.password" required>
              </div>
              <button type="submit" class="btn btn-primary">Save Changes</button>
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
      },
      editUserForm: {
        id: null,
        name: '',
        email: '',
        password: ''
      },
      editingUserId: null
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
            this.accounts = data.account;
          } else {
            console.error('No records found');
          }
        })
        .catch(error => {
          console.error('Error fetching accounts:', error);
        });
    },
    navigateToEdit(accountId) {
      this.editingUserId = accountId;
      fetch(`http://127.0.0.1:8000/api/account/${accountId}`, {
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
            this.editUserForm = data.account;
            $('#editUserModal').modal('show');
          } else {
            console.error('No user found');
          }
        })
        .catch(error => {
          console.error('Error fetching user data:', error);
        });
    },
    submitEditForm() {
      fetch(`http://127.0.0.1:8000/api/account/${this.editUserForm.id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(this.editUserForm)
      })
        .then(response => {
          if (!response.ok) {
            return response.text().then(text => { throw new Error(text) });
          }
          return response.json();
        })
        .then(data => {
          if (data.status === 200) {
            const index = this.accounts.findIndex(account => account.id === this.editUserForm.id);
            if (index !== -1) {
              this.accounts.splice(index, 1, this.editUserForm);
            }
            $('#editUserModal').modal('hide');
            Swal.fire('Updated!', 'User data has been updated successfully.', 'success');
          } else {
            console.error('Failed to update user data:', data.message);
            Swal.fire('Error!', 'Failed to update user data: ' + data.message, 'error');
          }
        })
        .catch(error => {
          console.error('Error updating user data:', error);
          Swal.fire('Error!', 'An error occurred while updating user data: ' + error.message, 'error');
        });
    },
    showModal() {
      $('#addUserModal').modal('show');
    }
  }
};
</script>
