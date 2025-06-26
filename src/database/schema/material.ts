import { Schema, model } from 'mongoose';

export interface IMaterial {
  tenantId: string;
  name: string;
  type: 'digital' | 'physical' | 'kit' | 'rental' | 'supplementary';
  agency?: string;
  languages: string[];
  version: string;
  inventoryId?: string; // Link to equipment/inventory if applicable
  fileUrl?: string; // For digital materials
  description?: string;
  price?: number;
  isActive: boolean;
  createdAt: Date;
  updatedAt: Date;
}

const MaterialSchema = new Schema<IMaterial>({
  tenantId: { type: String, required: true, index: true },
  name: { type: String, required: true },
  type: { type: String, required: true, enum: ['digital', 'physical', 'kit', 'rental', 'supplementary'] },
  agency: String,
  languages: [{ type: String }],
  version: { type: String, default: '1.0' },
  inventoryId: String,
  fileUrl: String,
  description: String,
  price: Number,
  isActive: { type: Boolean, default: true }
}, {
  timestamps: true
});

export const Material = model<IMaterial>('Material', MaterialSchema);
