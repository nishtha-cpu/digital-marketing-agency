const mongoose = require('mongoose');

const serviceSchema = new mongoose.Schema(
  {
    name: {
      type: String,
      required: [true, 'Please add a service name'],
      unique: true,
      trim: true,
    },
    description: {
      type: String,
      required: [true, 'Please add a description'],
      trim: true,
    },
    icon: {
      type: String,
      default: 'marketing', // Default representation icon identifier
    },
    features: [
      {
        type: String,
        trim: true,
      },
    ],
    price: {
      type: String, // String representation allows flexible pricing options like 'Contact us', '$499/mo', etc.
      default: 'Custom Pricing',
    },
    active: {
      type: Boolean,
      default: true,
    },
  },
  {
    timestamps: true,
  }
);

module.exports = mongoose.model('Service', serviceSchema);
