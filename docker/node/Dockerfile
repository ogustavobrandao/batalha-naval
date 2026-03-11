FROM node:20-alpine

# Set working directory
WORKDIR /var/www/html

# Use non-root node user provided by the alpine node image
USER node

# Default command to build assets
CMD ["sh", "-c", "npm install && npm run build"]
