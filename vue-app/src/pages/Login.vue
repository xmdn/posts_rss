<template>
    <div class="main">
      <div class="auth-block">
        <div class="main-block">
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
          <button class="btn-login" @click="login">
            <span>Login</span>
          </button>
          <button class="btn-register" @click="register">
            <span>Register</span>
          </button>
        </div>
      </div>
    </div>
  </template>

<script setup>
import { ref } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

const store = useStore();

const router = useRouter();

const email = ref('');
const password = ref('');


const login = async () => {
    console.log(email.value, password.value)
  try {
    
    await store.dispatch('login', { email: email.value, password: password.value });
    // Redirect or perform any other action upon successful login
    router.push('/');
  } catch (error) {
    console.error('Login failed:', error);
    // Handle login error, e.g., display error message to the user
  }
};

const register = () => {
  console.log('REGG')
  // Direct the user to the register page
  router.push('/register');
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
    background-color: #4e4ecb;
    padding: 10px;
    border-radius: 5px;
    color: aliceblue;
  }

  .btn-register {
    background-color: #545491;
    padding: 10px;
    border-radius: 5px;
    color: aliceblue;
  }
  </style>
  