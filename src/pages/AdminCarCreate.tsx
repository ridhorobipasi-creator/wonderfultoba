"use client";

import { useState, useEffect } from 'react';
import api from '../lib/api';
import { useRouter, useParams } from 'next/navigation';
import { 
  Car as CarIcon, Save, DollarSign, Users, ArrowLeft, 
  Droplet, Settings, Image as ImageIcon, FileText, 
  CheckCircle, Globe, LayoutList, Tags 
} from 'lucide-react';
import { useForm, useFieldArray } from 'react-hook-form';
import { toast } from 'sonner';
import BulkUpload from '@/components/BulkUpload';

interface PricingDetailForm {
  days: string;
  price: string;
}

interface AdminCarForm {
  name: string;
  type: string;
  seats: string | number;
  price_per_day: string | number;
  price_with_driver?: string | number;
  fuel?: string;
  transmission?: string;
  status: 'available' | 'booked' | 'maintenance';
  image?: string;
  description?: string;
  terms?: string;
  features?: string;
  includes?: string;
  is_featured: boolean;
  sort_order: string | number;
  meta_title?: string;
  meta_description?: string;
  pricing_details: PricingDetailForm[];
  translations: {
    en: Record<string, string>;
    ms: Record<string, string>;
  };
}

export default function AdminCarCreate() {
  const params = useParams<{ id?: string }>();
  const id = params?.id;
  const router = useRouter();
  const [activeTab, setActiveTab] = useState('info');
  const { register, handleSubmit, control, watch, reset, setValue } = useForm<AdminCarForm>({
    defaultValues: {
      status: 'available',
      is_featured: false,
      sort_order: 0,
      pricing_details: [{ days: '', price: '' }],
      translations: { en: {}, ms: {} },
      features: '',
      includes: ''
    }
  });

  const { fields, append, remove } = useFieldArray({
    control,
    name: "pricing_details"
  });

  useEffect(() => {
    if (id) {
      api.get(`/cars/${id}`).then(res => {
        const car = res.data;
        reset({
          ...car,
          features: car.features?.join(', ') || '',
          includes: car.includes?.join(', ') || '',
        });
      }).catch(err => {
        console.error('Error fetching car:', err);
        toast.error('Gagal mengambil data armada');
      });
    }
  }, [id, reset]);

  const onSubmit = async (data: AdminCarForm) => {
    try {
      // Parse arrays from strings
      const featuresArray = data.features ? data.features.split(',').map((s: string) => s.trim()) : [];
      const includesArray = data.includes ? data.includes.split(',').map((s: string) => s.trim()) : [];

      const payload = {
        name: data.name,
        type: data.type,
        capacity: Number(data.seats),
        price: Number(data.price_per_day),
        price_with_driver: data.price_with_driver ? Number(data.price_with_driver) : null,
        fuel: data.fuel,
        transmission: data.transmission,
        status: data.status,
        images: data.image ? data.image.split(',').map((s: string) => s.trim()) : [],
        description: data.description,
        terms: data.terms,
        features: featuresArray,
        includes: includesArray,
        is_featured: data.is_featured,
        sort_order: Number(data.sort_order),
        meta_title: data.meta_title,
        meta_description: data.meta_description,
        pricing_details: data.pricing_details.filter((p) => p.days && p.price),
        translations: data.translations
      };

      if (id) {
        await api.put(`/cars/${id}`, payload);
        toast.success('Armada berhasil diperbarui');
      } else {
        await api.post('/cars', payload);
        toast.success('Mobil baru berhasil ditambahkan');
      }
      router.push('/admin/cars');
    } catch (error) {
      console.error('Error saving car:', error);
      toast.error('Gagal menyimpan mobil');
    }
  };

  const tabs = [
    { id: 'info', label: 'Informasi', icon: <FileText size={18} /> },
    { id: 'harga', label: 'Harga & Promo', icon: <DollarSign size={18} /> },
    { id: 'spesifikasi', label: 'Spesifikasi', icon: <Settings size={18} /> },
    { id: 'fasilitas', label: 'Fasilitas & Ketentuan', icon: <CheckCircle size={18} /> },
    { id: 'seo', label: 'SEO & Tampilan', icon: <Globe size={18} /> },
    { id: 'bahasa', label: 'Terjemahan', icon: <LayoutList size={18} /> },
  ];

  return (
    <div className="max-w-6xl mx-auto pb-12">
      <button onClick={() => router.push('/admin/cars')} className="flex items-center gap-2 text-slate-500 hover:text-toba-green font-bold mb-6 transition-colors">
        <ArrowLeft size={18} /> Kembali ke Manajemen Mobil
      </button>

      <div className="bg-white rounded-[3rem] p-8 md:p-12 shadow-sm border border-slate-100">
        <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
          <div>
            <h2 className="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight mb-2">
              {id ? 'Edit Armada' : 'Tambah Kendaraan Baru'}
            </h2>
            <p className="text-slate-500 font-medium">Lengkapi detail armada dengan fitur premium.</p>
          </div>
          <button
            onClick={handleSubmit(onSubmit)}
            className="bg-toba-green text-white px-10 py-4 rounded-2xl font-bold hover:bg-toba-green/90 transition-all shadow-xl shadow-blue-100 flex items-center space-x-2"
          >
            <Save size={20} />
            <span>{id ? 'Simpan Perubahan' : 'Simpan Armada'}</span>
          </button>
        </div>

        {/* Tab Navigation */}
        <div className="flex overflow-x-auto hide-scrollbar border-b border-slate-100 mb-8 space-x-2">
          {tabs.map(tab => (
            <button
              key={tab.id}
              onClick={() => setActiveTab(tab.id)}
              className={`flex items-center space-x-2 px-6 py-4 border-b-2 font-bold transition-all whitespace-nowrap ${activeTab === tab.id ? 'border-toba-green text-toba-green' : 'border-transparent text-slate-400 hover:text-slate-600'}`}
            >
              {tab.icon}
              <span>{tab.label}</span>
            </button>
          ))}
        </div>

        <form className="space-y-8">
          {/* INFORMASI */}
          {activeTab === 'info' && (
            <div className="space-y-6 animate-in fade-in slide-in-from-bottom-4">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Nama Kendaraan</label>
                  <div className="relative">
                    <CarIcon className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                    <input
                      {...register('name', { required: true })}
                      className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900"
                      placeholder="Contoh: Toyota Innova Reborn"
                    />
                  </div>
                </div>
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Kategori / Tipe</label>
                  <div className="relative">
                    <Tags className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                    <input
                      {...register('type', { required: true })}
                      className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900"
                      placeholder="Contoh: SUV, MPV, Sedan"
                    />
                  </div>
                </div>
              </div>
              
              <div className="space-y-3">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Deskripsi Kendaraan</label>
                <textarea
                  {...register('description')}
                  rows={4}
                  className="w-full p-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-medium text-slate-900"
                  placeholder="Ceritakan kelebihan menyewa mobil ini..."
                />
              </div>

              <div className="space-y-3">
                <label className="text-xs font-black text-slate-400 uppercase tracking-widest ml-1">Foto Kendaraan</label>
                <BulkUpload
                  onUploadComplete={(urls) => {
                    const current = watch('image');
                    const newVal = current ? `${current}, ${urls.join(', ')}` : urls.join(', ');
                    setValue('image', newVal);
                  }}
                  maxFiles={5}
                />
                <div className="relative mt-2">
                  <ImageIcon className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                  <input
                    {...register('image')}
                    className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-medium text-slate-900"
                    placeholder="URL foto (pisahkan koma) - atau upload di atas"
                  />
                </div>
              </div>
            </div>
          )}

          {/* HARGA */}
          {activeTab === 'harga' && (
            <div className="space-y-6 animate-in fade-in slide-in-from-bottom-4">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Harga per Hari (Lepas Kunci)</label>
                  <div className="relative">
                    <DollarSign className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                    <input
                      type="number"
                      {...register('price_per_day', { required: true })}
                      className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900"
                      placeholder="500000"
                    />
                  </div>
                </div>
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Harga Dengan Supir</label>
                  <div className="relative">
                    <DollarSign className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                    <input
                      type="number"
                      {...register('price_with_driver')}
                      className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900"
                      placeholder="650000"
                    />
                  </div>
                </div>
              </div>

              <div className="pt-4 border-t border-slate-100">
                <h4 className="font-bold text-slate-900 mb-4">Pengaturan Harga Custom (Opsional)</h4>
                <div className="space-y-4">
                  {fields.map((field, index) => (
                    <div key={field.id} className="flex gap-4">
                      <div className="flex-1">
                        <input
                          {...register(`pricing_details.${index}.days`)}
                          placeholder="Jumlah Hari (mis. 3)"
                          className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-medium"
                        />
                      </div>
                      <div className="flex-1">
                        <input
                          {...register(`pricing_details.${index}.price`)}
                          placeholder="Harga per Hari"
                          className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-medium"
                        />
                      </div>
                      <button type="button" onClick={() => remove(index)} className="px-4 text-rose-500 hover:bg-rose-50 rounded-2xl transition-colors font-bold">Hapus</button>
                    </div>
                  ))}
                  <button type="button" onClick={() => append({ days: '', price: '' })} className="text-toba-green font-bold text-sm bg-blue-50 px-6 py-3 rounded-xl hover:bg-blue-100 transition-colors">
                    + Tambah Harga Durasi Khusus
                  </button>
                </div>
              </div>
            </div>
          )}

          {/* SPESIFIKASI */}
          {activeTab === 'spesifikasi' && (
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 animate-in fade-in slide-in-from-bottom-4">
              <div className="space-y-3">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Kapasitas (Kursi)</label>
                <div className="relative">
                  <Users className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                  <input
                    type="number"
                    {...register('seats')}
                    className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900"
                    placeholder="7"
                  />
                </div>
              </div>
              <div className="space-y-3">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Sistem BBM</label>
                <div className="relative">
                  <Droplet className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                  <select
                    {...register('fuel')}
                    className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900 appearance-none"
                  >
                    <option value="Bensin">Bensin</option>
                    <option value="Solar">Solar</option>
                    <option value="Hybrid">Hybrid</option>
                    <option value="EV / Listrik">EV / Listrik</option>
                  </select>
                </div>
              </div>
              <div className="space-y-3">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Transmisi</label>
                <div className="relative">
                  <Settings className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                  <select
                    {...register('transmission')}
                    className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900 appearance-none"
                  >
                    <option value="Manual">Manual</option>
                    <option value="Otomatis">Otomatis</option>
                    <option value="CVT">CVT</option>
                  </select>
                </div>
              </div>
            </div>
          )}

          {/* FASILITAS */}
          {activeTab === 'fasilitas' && (
            <div className="space-y-6 animate-in fade-in slide-in-from-bottom-4">
              <div className="space-y-3">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Fitur Kendaraan (Pisahkan dengan koma)</label>
                <input
                  {...register('features')}
                  className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-medium"
                  placeholder="AC, Audio Premium, USB Charger, Sunroof..."
                />
              </div>
              <div className="space-y-3">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Termasuk dalam harga (Pisahkan dengan koma)</label>
                <input
                  {...register('includes')}
                  className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-medium"
                  placeholder="Asuransi pihak ketiga, Masker, Hand Sanitizer..."
                />
              </div>
              <div className="space-y-3">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Syarat & Ketentuan</label>
                <textarea
                  {...register('terms')}
                  rows={4}
                  className="w-full p-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-medium"
                  placeholder="KTP wajib diserahkan, dilarang merokok di dalam..."
                />
              </div>
            </div>
          )}

          {/* SEO & TAMPILAN */}
          {activeTab === 'seo' && (
            <div className="space-y-6 animate-in fade-in slide-in-from-bottom-4">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Status Ketersediaan</label>
                  <select
                    {...register('status')}
                    className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900 appearance-none"
                  >
                    <option value="available">Tersedia</option>
                    <option value="booked">Disewa / Booked</option>
                    <option value="maintenance">Maintenance</option>
                  </select>
                </div>
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Urutan Tampil (Sort Order)</label>
                  <input
                    type="number"
                    {...register('sort_order')}
                    className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-bold text-slate-900"
                    placeholder="0"
                  />
                </div>
              </div>

              <div className="flex items-center space-x-3 bg-slate-50 p-6 rounded-2xl">
                <input
                  type="checkbox"
                  {...register('is_featured')}
                  id="is_featured"
                  className="w-5 h-5 text-toba-green focus:ring-toba-green border-slate-300 rounded"
                />
                <label htmlFor="is_featured" className="font-bold text-slate-900">Jadikan Kendaraan Unggulan (Featured)</label>
              </div>

              <div className="pt-4 border-t border-slate-100 space-y-4">
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Meta Title (SEO)</label>
                  <input
                    {...register('meta_title')}
                    className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-medium"
                    placeholder="Sewa Toyota Innova Murah..."
                  />
                </div>
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Meta Description (SEO)</label>
                  <textarea
                    {...register('meta_description')}
                    rows={2}
                    className="w-full p-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green font-medium"
                    placeholder="Rental mobil toyota innova lepas kunci harian..."
                  />
                </div>
              </div>
            </div>
          )}

          {/* TERJEMAHAN */}
          {activeTab === 'bahasa' && (
            <div className="grid grid-cols-1 md:grid-cols-2 gap-8 animate-in fade-in slide-in-from-bottom-4">
              <div className="space-y-6 bg-slate-50 p-6 rounded-3xl">
                <div className="flex items-center space-x-2 border-b border-slate-200 pb-4">
                  <span className="text-2xl">🇺🇸</span>
                  <h3 className="font-bold text-slate-900">English (EN)</h3>
                </div>
                <div className="space-y-4">
                  <input
                    {...register('translations.en.name')}
                    className="w-full px-4 py-3 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Car Name (EN)"
                  />
                  <textarea
                    {...register('translations.en.description')}
                    rows={3}
                    className="w-full p-4 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Description (EN)"
                  />
                </div>
              </div>

              <div className="space-y-6 bg-slate-50 p-6 rounded-3xl">
                <div className="flex items-center space-x-2 border-b border-slate-200 pb-4">
                  <span className="text-2xl">🇲🇾</span>
                  <h3 className="font-bold text-slate-900">Malaysia (MS)</h3>
                </div>
                <div className="space-y-4">
                  <input
                    {...register('translations.ms.name')}
                    className="w-full px-4 py-3 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Nama Kereta (MS)"
                  />
                  <textarea
                    {...register('translations.ms.description')}
                    rows={3}
                    className="w-full p-4 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Penerangan (MS)"
                  />
                </div>
              </div>
            </div>
          )}
        </form>
      </div>
    </div>
  );
}
