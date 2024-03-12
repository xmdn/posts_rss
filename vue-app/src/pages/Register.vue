<template>
    <div class="main">
      <div class="auth-block">
        <div class="main-block">
          <div class="input-block">
            <span>Name</span>
            <input type="text" v-model="name">
          </div>
          <div class="input-block">
            <span>Email</span>
            <input type="text" v-model="email">
          </div>
          <div class="input-block">
            <span>Password</span>
            <input type="password" v-model="password">
          </div>
        </div>
        
        <div class="btn-container">
          <button class="btn-register" @click="registerUser">
            <span>Register</span>
          </button>
          <button class="btn-login">
            <span>Login</span>
          </button>
          
        </div>
        <div></div>
      </div>
    </div>
  </template>

<script setup>
  import { ref } from 'vue';
  import { useStore } from 'vuex';
  import { useRouter } from 'vue-router';

  const store = useStore();
  const router = useRouter();

  const name = ref('');
  const email = ref('');
  const password = ref('');

  const registerUser = () => {
    store.dispatch('register', { name: name.value, email: email.value, password: password.value })
      .then(response => {
        // Registration successful, navigate to the appropriate page
        router.push('/'); // Change this to the desired route after registration
      })
      .catch(error => {
        // Handle registration error
        console.error('Registration error:', error);
      });
  };
</script>
  
  <style scoped>
  .main {
    display: flex;
    justify-content: center;
    align-items: center; /* Vertically center children */
    height: 62vw;
  }
  
  .main-block {
    display: flex;
    height: 60%;
    flex-direction: column;
    justify-content: space-evenly;
  }
  .btn-container {
    display: flex;
    width: 60%;
    justify-content: space-evenly;
    height: auto;
  }
  
  .input-block {
    display: flex;
    flex-direction: column;
  }
  
  .auth-block {
    -webkit-box-shadow: 2px 3px 33px -7px rgba(0,0,0,0.6);
    -moz-box-shadow: 2px 3px 33px -7px rgba(0,0,0,0.6);
    box-shadow: 2px 3px 33px -7px rgba(0,0,0,0.6);
    border-radius: 10px;
    display: flex;
    height: 347px;
    width: 326px;
    flex-direction: column;
    justify-content: space-evenly;
    align-items: center;
  }
  
  .btn-login {
    background-color: #545491;
    padding: 10px;
    border-radius: 5px;
    color: aliceblue;
  }

  .btn-register {
    background-color: #4e4ecb;
    padding: 10px;
    border-radius: 5px;
    color: aliceblue;


  }
  </style>
  