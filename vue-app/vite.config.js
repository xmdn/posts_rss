import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'

import dotenv from 'dotenv';

dotenv.config({ path: '../../.env' });

// https://vitejs.dev/config/
export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd(), '');
  return {
    define: {
      'process.env.API_BASE_URL': JSON.stringify(env.API_BASE_URL)
    },
    plugins: [vue()],
    server: {
      port: 8081,
      watch: {
        usePolling: true
      }
    },
  }
})