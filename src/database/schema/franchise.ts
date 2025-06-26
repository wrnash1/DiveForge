import { Schema, model } from 'mongoose';

export interface IFranchise {
  tenantId: string;
  name: string;
  code: string;
  owner: string;
  contactEmail: string;
  contactPhone?: string;
  address: string;
  region?: string;
  status: 'active' | 'inactive' | 'suspended';
  locations: string[]; // location IDs
  settings?: Record<string, any>;
  notes?: string;
  createdAt: Date;
  updatedAt: Date;
}

const FranchiseSchema = new Schema<IFranchise>({
  tenantId: { type: String, required: true, index: true },
  name: { type: String, required: true },
  code: { type: String, required: true, unique: true },
  owner: { type: String, required: true },
  contactEmail: { type: String, required: true },
  contactPhone: String,
  address: { type: String, required: true },
  region: String,
  status: { type: String, enum: ['active', 'inactive', 'suspended'], default: 'active' },
  locations: [String],
  settings: Schema.Types.Mixed,
  notes: String
}, {
  timestamps: true
});

export const Franchise = model<IFranchise>('Franchise', FranchiseSchema);
