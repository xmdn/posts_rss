# Use Node.js version 18 Alpine as base image
FROM node:18-alpine

# Set the working directory inside the container
WORKDIR /app

# Copy package.json and package-lock.json to work directory
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of the application code
COPY . .

# Build the Vue.js application
RUN npm run build

# Expose the port that the app runs on
EXPOSE 8081

# Command to run the app
CMD ["npm", "run", "dev"]
