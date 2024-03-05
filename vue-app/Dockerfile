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

# Expose the port that the app runs on (if necessary)
# EXPOSE 8080

# Run the app
CMD ["npm", "run", "serve"]
