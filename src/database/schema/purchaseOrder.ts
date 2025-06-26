import { Schema, model } from 'mongoose';

export interface IPurchaseOrder {
  tenantId: string;
  vendorId: string;
  orderNumber: string;
  items: Array<{
    equipmentId?: string;
    description: string;
    quantity: number;
    unitPrice: number;
    total: number;
  }>;
  status: 'draft' | 'sent' | 'received' | 'cancelled' | 'partial';
  orderDate: Date;
  expectedDate?: Date;
  receivedDate?: Date;
  notes?: string;
  createdAt: Date;
  updatedAt: Date;
}

const PurchaseOrderSchema = new Schema<IPurchaseOrder>({
  tenantId: { type: String, required: true, index: true },
  vendorId: { type: String, required: true },
  orderNumber: { type: String, required: true },
  items: [{
    equipmentId: String,
    description: { type: String, required: true },
    quantity: { type: Number, required: true },
    unitPrice: { type: Number, required: true },
    total: { type: Number, required: true }
  }],
  status: { type: String, enum: ['draft', 'sent', 'received', 'cancelled', 'partial'], default: 'draft' },
  orderDate: { type: Date, required: true },
  expectedDate: Date,
  receivedDate: Date,
  notes: String
}, {
  timestamps: true
});

export const PurchaseOrder = model<IPurchaseOrder>('PurchaseOrder', PurchaseOrderSchema);
