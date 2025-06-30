import { Schema, model } from 'mongoose';

export interface IOrganization {
  name: string;
  code: string;
  parentOrgId?: string;
  type: 'corporate' | 'franchise' | 'location' | 'group' | 'other';
  contactEmail: string;
  contactPhone?: string;
  address?: string;
  region?: string;
  status: 'active' | 'inactive' | 'suspended';
  settings?: Record<string, any>;
  notes?: string;
  createdAt: Date;
  updatedAt: Date;
}

const OrganizationSchema = new Schema<IOrganization>({
  name: { type: String, required: true },
  code: { type: String, required: true, unique: true },
  parentOrgId: String,
  type: { type: String, enum: ['corporate', 'franchise', 'location', 'group', 'other'], required: true },
  contactEmail: { type: String, required: true },
  contactPhone: String,
  address: String,
  region: String,
  status: { type: String, enum: ['active', 'inactive', 'suspended'], default: 'active' },
  settings: Schema.Types.Mixed,
  notes: String
}, {
  timestamps: true
});

export const Organization = model<IOrganization>('Organization', OrganizationSchema);
