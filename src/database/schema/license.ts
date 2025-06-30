import { Schema, model } from 'mongoose';

export interface ILicense {
  tenantId: string;
  type: 'agency' | 'business' | 'boat' | 'staff' | 'equipment' | 'other';
  name: string;
  number: string;
  issuedBy: string;
  issuedTo?: string;
  validFrom: Date;
  validTo: Date;
  status: 'active' | 'expired' | 'revoked' | 'pending';
  fileUrl?: string;
  notes?: string;
  createdAt: Date;
  updatedAt: Date;
}

const LicenseSchema = new Schema<ILicense>({
  tenantId: { type: String, required: true, index: true },
  type: { type: String, enum: ['agency', 'business', 'boat', 'staff', 'equipment', 'other'], required: true },
  name: { type: String, required: true },
  number: { type: String, required: true },
  issuedBy: { type: String, required: true },
  issuedTo: String,
  validFrom: { type: Date, required: true },
  validTo: { type: Date, required: true },
  status: { type: String, enum: ['active', 'expired', 'revoked', 'pending'], default: 'active' },
  fileUrl: String,
  notes: String
}, {
  timestamps: true
});

export const License = model<ILicense>('License', LicenseSchema);
