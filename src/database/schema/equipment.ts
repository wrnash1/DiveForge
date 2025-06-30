import { Schema, model } from 'mongoose';

export interface IEquipment {
  tenantId: string;
  name: string;
  type: string;
  brand?: string;
  model?: string;
  serialNumber?: string;
  status: 'available' | 'rented' | 'maintenance' | 'retired';
  location?: string;
  purchaseDate?: Date;
  warrantyExpiry?: Date;
  serviceHistory: Array<{
    date: Date;
    description: string;
    technician?: string;
    cost?: number;
  }>;
  images?: string[];
  isRental: boolean;
  isForSale: boolean;
  price?: number;
  createdAt: Date;
  updatedAt: Date;
}

const EquipmentSchema = new Schema<IEquipment>({
  tenantId: { type: String, required: true, index: true },
  name: { type: String, required: true },
  type: { type: String, required: true },
  brand: String,
  model: String,
  serialNumber: String,
  status: { type: String, enum: ['available', 'rented', 'maintenance', 'retired'], default: 'available' },
  location: String,
  purchaseDate: Date,
  warrantyExpiry: Date,
  serviceHistory: [{
    date: Date,
    description: String,
    technician: String,
    cost: Number
  }],
  images: [String],
  isRental: { type: Boolean, default: false },
  isForSale: { type: Boolean, default: false },
  price: Number
}, {
  timestamps: true
});

export const Equipment = model<IEquipment>('Equipment', EquipmentSchema);
