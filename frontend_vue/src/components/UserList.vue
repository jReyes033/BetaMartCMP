<template>
  <div class="UserList">
    <h2>User List</h2>
    <button class="btn btn-primary add-user-button" @click="addUser">Add User</button>
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
  </div>
</template>

<script>
import Swal from 'sweetalert2';

export default {
  name: 'UserList',
  data() {
    return {
      accounts: [] // Renamed from 'users' to 'accounts'
    };
  },
  mounted() {
    this.fetchAccounts(); // Adjusted method name
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
            this.accounts = data.account; // Updated to populate 'accounts' array
          } else {
            console.error('No records found');
          }
        })
        .catch(error => {
          console.error('Error fetching accounts:', error);
        });
    },
    navigateToEdit(accountId) {
      // Redirect to the edit user page
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
      }).then((result) => {
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
          // Remove the deleted account from the accounts array
          this.accounts = this.accounts.filter(account => account.id !== accountId);
          // Show a success notification
          Swal.fire('Deleted!', 'Account has been deleted.', 'success');
        })
        .catch(error => {
          console.error('Error deleting account:', error);
          Swal.fire('Error!', 'Failed to delete account.', 'error');
        });
    },
    addUser() {
      // Implement functionality to add a user
    }
  }
}
</script>
