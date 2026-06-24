const mongoose = require('mongoose');

const connectDB = async () => {
  const mongoURI = process.env.MONGO_URI;
  const fallbackURI = process.env.MONGO_URI_FALLBACK || 'mongodb://127.0.0.1:27017/dm-agency';

  if (!mongoURI) {
    console.error('Error: MONGO_URI is not defined in the environment variables.');
    process.exit(1);
  }

  // Mask database credentials in connection URI for logging
  const maskURI = (uri) => {
    return uri.replace(/(mongodb(?:\+srv)?:\/\/[^:]+:)([^@]+)(@)/, '$1******$3');
  };

  try {
    console.log(`Attempting to connect to MongoDB using primary URI: ${maskURI(mongoURI)}`);
    const conn = await mongoose.connect(mongoURI);
    console.log(`MongoDB Connected: ${conn.connection.host}`);
  } catch (error) {
    console.error(`MongoDB connection failed with primary URI: ${error.message}`);
    
    // Check if error is related to SRV DNS resolution (querySrv ECONNREFUSED, ENOTFOUND, EREFUSED)
    const isSrvError = error.message.includes('querySrv') || 
                       error.message.includes('ECONNREFUSED') || 
                       error.message.includes('ENOTFOUND') ||
                       error.message.includes('EREFUSED');
    const isSrvUri = mongoURI.startsWith('mongodb+srv://');

    if (isSrvUri && isSrvError) {
      console.warn('Detected querySrv ECONNREFUSED or DNS lookup failure with mongodb+srv URI.');
      console.warn(`Attempting connection using standard fallback URI: ${maskURI(fallbackURI)}`);
      try {
        const connFallback = await mongoose.connect(fallbackURI);
        console.log(`MongoDB Connected (Fallback): ${connFallback.connection.host}`);
      } catch (fallbackError) {
        console.error(`MongoDB connection failed with fallback URI: ${fallbackError.message}`);
        process.exit(1);
      }
    } else {
      process.exit(1);
    }
  }
};

module.exports = connectDB;
