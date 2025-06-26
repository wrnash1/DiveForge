import { Schema, model } from 'mongoose';

export interface IVendor {
  tenantId: string;
  name: string;
  contactName?: string;
  email?: string;
  phone?: string;
  address?: string;
  catalogs: Array<{
    catalogName: string;
    url?: string;
    lastSync?: Date;
  }>;
  preferred: boolean;
  notes?: string;
  createdAt: Date;
  updatedAt: Date;
}

const VendorSchema = new Schema<IVendor>({
  tenantId: { type: String, required: true, index: true },
  name: { type: String, required: true },
  contactName: String,
  email: String,
  phone: String,
  address: String,
  catalogs: [{
    catalogName: String,
    url: String,
    lastSync: Date
  }],
  preferred: { type: Boolean, default: false },
  notes: String
}, {
  timestamps: true
});

export const Vendor = model<IVendor>('Vendor', VendorSchema);
