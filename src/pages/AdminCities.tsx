"use client";


import { useState, useEffect } from 'react';
import api from '../lib/api';
import { motion, AnimatePresence } from 'framer-motion';
import { Plus, Edit2, Trash2, Search, MapPin, X, Save, Globe, Flag, Building2, Navigation } from 'lucide-react';
import { useForm } from 'react-hook-form';
import { cn } from '../utils/cn';
import { toast } from 'sonner';
import { PROVINCES, SUMUT_DISTRICTS, POPULAR_COUNTRIES } from '../data/indonesia';

interface CityData {
  id: number;
  name: string;
  slug: string;
  type: string;
  country: string;
  region?: string;
  district?: string;
  place?: string;
  description?: string;
}

interface CityForm {
  type: 'domestic' | 'international';
  // Domestic
  province: string;
  district: string;       // dropdown (Sumut) atau manual (lainnya)
  districtManual: string; // manual jika bukan Sumut
  place: string;          // kota/tempat spesifik - selalu manual
  // International
  country: string;
  countryManual: string;
  region: string;         // provinsi/wilayah negara - manual
  placeIntl: string;      // kota - manual
  // Shared
  description: string;
}

function buildName(f: CityForm): string {
  if (f.type === 'international') {
    const country = f.country === '__manual__' ? f.countryManual : f.country;
    const parts = [f.placeIntl, f.region, country].filter(Boolean);
    return parts.join(', ');
  }
  // domestic
  const isSumut = f.province === 'Sumatera Utara';
  const district = isSumut
    ? (f.district === '__manual__' ? f.districtManual : f.district)
    : f.districtManual;
  const parts = [f.place, district, f.province !== 'Sumatera Utara' ? f.province : ''].filter(Boolean);
  return parts.length > 0 ? parts.join(', ') : f.province || '';
}

export default function AdminCities() {
  const [cities, setCities] = useState<CityData[]>([]);
  const [loading, setLoading] = useState(true);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editingCity, setEditingCity] = useState<CityData | null>(null);
  const [searchQuery, setSearchQuery] = useState('');
  const [filterType, setFilterType] = useState<'all' | 'domestic' | 'international'>('all');

  const { register, handleSubmit, reset, watch, setValue } = useForm<CityForm>({
    defaultValues: {
      type: 'domestic',
      province: 'Sumatera Utara',
      district: '',
      districtManual: '',
      place: '',
      country: '',
      countryManual: '',
      region: '',
      placeIntl: '',
      description: '',
    },
  });

  const watchType = watch('type');
  const watchProvince = watch('province');
  const watchDistrict = watch('district');
  const watchCountry = watch('country');
  const isSumut = watchProvince === 'Sumatera Utara';

  useEffect(() => { fetchCities(); }, []);

  const fetchCities = async () => {
    setLoading(true);
    try {
      const res = await api.get('/cities');
      setCities(res.data);
    } catch {
      toast.error('Gagal memuat data wilayah');
    } finally {
      setLoading(false);
    }
  };

  const onSubmit = async (data: CityForm) => {
    try {
      const isSumutForm = data.province === 'Sumatera Utara';
      const district = data.type === 'domestic'
        ? (isSumutForm
            ? (data.district === '__manual__' ? data.districtManual : data.district)
            : data.districtManual)
        : null;
      const country = data.type === 'international'
        ? (data.country === '__manual__' ? data.countryManual : data.country)
        : 'Indonesia';
      const name = buildName(data);

      const payload = {
        name,
        type: data.type,
        country,
        region: data.type === 'domestic' ? data.province : (data.region || null),
        district: district || null,
        place: data.type === 'domestic' ? (data.place || null) : (data.placeIntl || null),
        description: data.description || null,
      };

      if (editingCity) {
        await api.put(`/cities/${editingCity.id}`, payload);
        toast.success('Wilayah diperbarui');
      } else {
        await api.post('/cities', payload);
        toast.success('Wilayah ditambahkan');
      }
      setIsModalOpen(false);
      setEditingCity(null);
      reset();
      fetchCities();
    } catch {
      toast.error('Gagal menyimpan wilayah');
    }
  };

  const handleEdit = (city: CityData) => {
    setEditingCity(city);
    const isIntl = city.type === 'international';
    const isSumutCity = city.region === 'Sumatera Utara';
    const isManualDistrict = city.district && !SUMUT_DISTRICTS.includes(city.district);
    const isManualCountry = city.country && city.country !== 'Indonesia' && !POPULAR_COUNTRIES.includes(city.country);

    reset({
      type: isIntl ? 'international' : 'domestic',
      province: city.region || 'Sumatera Utara',
      district: isSumutCity ? (isManualDistrict ? '__manual__' : (city.district || '')) : '',
      districtManual: (isManualDistrict || !isSumutCity) ? (city.district || '') : '',
      place: !isIntl ? (city.place || '') : '',
      country: isIntl ? (isManualCountry ? '__manual__' : (city.country || '')) : '',
      countryManual: isManualCountry ? city.country : '',
      region: isIntl ? (city.region || '') : '',
      placeIntl: isIntl ? (city.place || '') : '',
      description: city.description || '',
    });
    setIsModalOpen(true);
  };

  const handleDelete = async (id: number) => {
    if (!window.confirm('Hapus wilayah ini? Paket yang terkait mungkin terpengaruh.')) return;
    try {
      await api.delete(`/cities/${id}`);
      toast.success('Wilayah dihapus');
      fetchCities();
    } catch {
      toast.error('Gagal menghapus. Pastikan tidak ada paket yang menggunakan wilayah ini.');
    }
  };

  const filtered = cities.filter(c => {
    const matchType = filterType === 'all' || c.type === filterType;
    const q = searchQuery.toLowerCase();
    const matchSearch = !q || c.name.toLowerCase().includes(q) ||
      c.district?.toLowerCase().includes(q) ||
      c.country?.toLowerCase().includes(q) ||
      c.region?.toLowerCase().includes(q) ||
      c.place?.toLowerCase().includes(q);
    return matchType && matchSearch;
  });

  const domesticCount = cities.filter(c => c.type === 'domestic' || !c.type).length;
  const intlCount = cities.filter(c => c.type === 'international').length;

  return (
    <div className="space-y-8 pb-12">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
          <h2 className="text-3xl font-black text-slate-900 tracking-tight">Manajemen Wilayah</h2>
          <p className="text-slate-500 font-medium mt-1">Kelola destinasi domestik (seluruh Indonesia) dan internasional</p>
        </div>
        <button
          onClick={() => { setEditingCity(null); reset(); setIsModalOpen(true); }}
          className="bg-toba-green text-white px-6 py-3.5 rounded-2xl font-bold flex items-center gap-2 hover:bg-toba-green/90 transition-all shadow-lg shadow-toba-green/20"
        >
          <Plus size={18} /> Tambah Wilayah
        </button>
      </div>

      {/* Stats */}
      <div className="grid grid-cols-3 gap-4">
        {[
          { label: 'Total Wilayah', value: cities.length, icon: MapPin, color: 'text-toba-green', bg: 'bg-toba-green/10' },
          { label: 'Domestik', value: domesticCount, icon: Flag, color: 'text-blue-600', bg: 'bg-blue-50' },
          { label: 'Internasional', value: intlCount, icon: Globe, color: 'text-purple-600', bg: 'bg-purple-50' },
        ].map((s, i) => (
          <div key={i} className="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div className={cn('p-3 rounded-xl', s.bg)}>
              <s.icon size={22} className={s.color} />
            </div>
            <div>
              <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest">{s.label}</p>
              <p className="text-2xl font-black text-slate-900">{s.value}</p>
            </div>
          </div>
        ))}
      </div>

      {/* Filter & Search */}
      <div className="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col sm:flex-row gap-4 items-center">
        <div className="relative flex-1">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={16} />
          <input
            type="text"
            placeholder="Cari nama, kabupaten, provinsi, negara..."
            value={searchQuery}
            onChange={e => setSearchQuery(e.target.value)}
            className="w-full pl-11 pr-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-toba-green font-medium text-sm"
          />
        </div>
        <div className="flex gap-2 shrink-0">
          {(['all', 'domestic', 'international'] as const).map(t => (
            <button key={t} onClick={() => setFilterType(t)}
              className={cn('px-4 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all',
                filterType === t ? 'bg-toba-green text-white shadow-lg shadow-toba-green/20' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'
              )}>
              {t === 'all' ? 'Semua' : t === 'domestic' ? '🇮🇩 Domestik' : '✈️ Internasional'}
            </button>
          ))}
        </div>
      </div>

      {/* Table */}
      <div className="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <table className="w-full">
          <thead>
            <tr className="bg-slate-50/50 border-b border-slate-100">
              <th className="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Tampilan</th>
              <th className="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest hidden md:table-cell">Tipe</th>
              <th className="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest hidden lg:table-cell">Provinsi / Negara</th>
              <th className="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest hidden lg:table-cell">Kab/Kota · Tempat</th>
              <th className="px-6 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-50">
            {loading ? (
              <tr><td colSpan={5} className="py-20 text-center">
                <div className="w-10 h-10 border-4 border-toba-green/20 border-t-toba-green rounded-full animate-spin mx-auto mb-3" />
                <p className="text-slate-400 text-sm">Memuat data...</p>
              </td></tr>
            ) : filtered.length === 0 ? (
              <tr><td colSpan={5} className="py-20 text-center">
                <MapPin size={40} className="mx-auto text-slate-200 mb-3" />
                <p className="text-slate-400 font-medium">Tidak ada wilayah ditemukan</p>
                <button onClick={() => setIsModalOpen(true)} className="mt-3 text-toba-green font-bold text-sm hover:underline">
                  + Tambah wilayah pertama
                </button>
              </td></tr>
            ) : filtered.map((city, i) => (
              <motion.tr key={city.id}
                initial={{ opacity: 0, y: 6 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: i * 0.03 }}
                className="hover:bg-slate-50/50 transition-colors group"
              >
                <td className="px-6 py-4">
                  <div className="flex items-center gap-3">
                    <div className={cn('w-9 h-9 rounded-xl flex items-center justify-center text-base shrink-0',
                      city.type === 'international' ? 'bg-purple-50' : 'bg-toba-green/10')}>
                      {city.type === 'international' ? '✈️' : '🏔️'}
                    </div>
                    <div>
                      <p className="font-bold text-slate-900 text-sm leading-tight">{city.name}</p>
                      {city.description && <p className="text-xs text-slate-400 truncate max-w-[180px]">{city.description}</p>}
                    </div>
                  </div>
                </td>
                <td className="px-6 py-4 hidden md:table-cell">
                  <span className={cn('px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider',
                    city.type === 'international' ? 'bg-purple-50 text-purple-600' : 'bg-toba-green/10 text-toba-green')}>
                    {city.type === 'international' ? 'Internasional' : 'Domestik'}
                  </span>
                </td>
                <td className="px-6 py-4 hidden lg:table-cell">
                  <p className="text-sm font-medium text-slate-600">
                    {city.type === 'domestic' ? (city.region || 'Indonesia') : city.country}
                  </p>
                </td>
                <td className="px-6 py-4 hidden lg:table-cell">
                  <p className="text-xs text-slate-500">
                    {[city.district, city.place].filter(Boolean).join(' · ') || '-'}
                  </p>
                </td>
                <td className="px-6 py-4 text-right">
                  <div className="flex justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onClick={() => handleEdit(city)} className="p-2 text-slate-400 hover:text-toba-green hover:bg-toba-green/10 rounded-xl transition-all">
                      <Edit2 size={15} />
                    </button>
                    <button onClick={() => handleDelete(city.id)} className="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                      <Trash2 size={15} />
                    </button>
                  </div>
                </td>
              </motion.tr>
            ))}
          </tbody>
        </table>
      </div>

      {/* Modal */}
      <AnimatePresence>
        {isModalOpen && (
          <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md">
            <motion.div
              initial={{ opacity: 0, scale: 0.95, y: 20 }}
              animate={{ opacity: 1, scale: 1, y: 0 }}
              exit={{ opacity: 0, scale: 0.95, y: 20 }}
              className="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl overflow-hidden max-h-[92vh] overflow-y-auto"
            >
              <div className="p-7 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 sticky top-0 z-10">
                <div>
                  <h3 className="text-xl font-black text-slate-900">{editingCity ? 'Edit Wilayah' : 'Tambah Wilayah'}</h3>
                  <p className="text-xs text-slate-400 mt-0.5">Isi detail lokasi destinasi</p>
                </div>
                <button onClick={() => setIsModalOpen(false)} className="p-2 hover:bg-white rounded-xl text-slate-400 hover:text-slate-900 transition-all">
                  <X size={20} />
                </button>
              </div>

              <form onSubmit={handleSubmit(onSubmit)} className="p-7 space-y-5">

                {/* Tipe */}
                <div className="space-y-2">
                  <label className="text-xs font-black text-slate-400 uppercase tracking-widest">Tipe Destinasi</label>
                  <div className="grid grid-cols-2 gap-3">
                    {[
                      { val: 'domestic', emoji: '🇮🇩', label: 'Domestik', sub: 'Seluruh Indonesia' },
                      { val: 'international', emoji: '✈️', label: 'Internasional', sub: 'Luar Negeri' },
                    ].map(opt => (
                      <label key={opt.val} className={cn(
                        'flex flex-col items-center p-4 rounded-2xl border-2 cursor-pointer transition-all',
                        watchType === opt.val ? 'border-toba-green bg-toba-green/5' : 'border-slate-200 hover:border-slate-300'
                      )}>
                        <input type="radio" value={opt.val} {...register('type')} className="hidden" />
                        <span className="text-2xl mb-1">{opt.emoji}</span>
                        <span className="font-black text-sm text-slate-900">{opt.label}</span>
                        <span className="text-xs text-slate-400">{opt.sub}</span>
                      </label>
                    ))}
                  </div>
                </div>

                {/* ===== DOMESTIC ===== */}
                {watchType === 'domestic' && (
                  <>
                    {/* Provinsi - dropdown semua 38 */}
                    <div className="space-y-2">
                      <label className="text-xs font-black text-slate-400 uppercase tracking-widest">
                        Provinsi <span className="text-rose-500">*</span>
                      </label>
                      <select {...register('province', { required: true })}
                        className="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-900 focus:ring-2 focus:ring-toba-green appearance-none">
                        <option value="">-- Pilih Provinsi --</option>
                        {PROVINCES.map(p => (
                          <option key={p.id} value={p.name}>{p.name}</option>
                        ))}
                      </select>
                    </div>

                    {/* Kabupaten/Kota */}
                    <div className="space-y-2">
                      <label className="text-xs font-black text-slate-400 uppercase tracking-widest">
                        Kabupaten / Kota
                        {isSumut && <span className="ml-1 text-toba-green font-normal">(dropdown Sumut)</span>}
                        {!isSumut && watchProvince && <span className="ml-1 text-slate-400 font-normal">(isi manual)</span>}
                      </label>

                      {/* Sumut: dropdown lengkap */}
                      {isSumut && (
                        <select {...register('district')}
                          className="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-900 focus:ring-2 focus:ring-toba-green appearance-none">
                          <option value="">-- Pilih Kab/Kota Sumut --</option>
                          {SUMUT_DISTRICTS.map(d => (
                            <option key={d} value={d}>{d}</option>
                          ))}
                          <option value="__manual__">✏️ Ketik Manual...</option>
                        </select>
                      )}

                      {/* Sumut manual override */}
                      {isSumut && watchDistrict === '__manual__' && (
                        <input {...register('districtManual')}
                          className="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl font-medium text-slate-900 focus:ring-2 focus:ring-toba-green"
                          placeholder="Ketik nama kabupaten/kota..." />
                      )}

                      {/* Non-Sumut: selalu manual */}
                      {!isSumut && watchProvince && (
                        <input {...register('districtManual')}
                          className="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl font-medium text-slate-900 focus:ring-2 focus:ring-toba-green"
                          placeholder={`Contoh: Kab. Badung, Kota Denpasar...`} />
                      )}
                    </div>

                    {/* Tempat spesifik - selalu manual */}
                    <div className="space-y-2">
                      <label className="text-xs font-black text-slate-400 uppercase tracking-widest">
                        Kota / Tempat Spesifik
                        <span className="text-slate-400 font-normal ml-1">(opsional, selalu manual)</span>
                      </label>
                      <div className="relative">
                        <Building2 className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={15} />
                        <input {...register('place')}
                          className="w-full pl-11 pr-4 py-3.5 bg-slate-50 border-none rounded-2xl font-medium text-slate-900 focus:ring-2 focus:ring-toba-green"
                          placeholder="Contoh: Parapat, Berastagi, Bukit Lawang, Ubud..." />
                      </div>
                      <p className="text-xs text-slate-400 ml-1">Nama tempat wisata atau kota spesifik di dalam kabupaten</p>
                    </div>
                  </>
                )}

                {/* ===== INTERNATIONAL ===== */}
                {watchType === 'international' && (
                  <>
                    {/* Negara - dropdown populer + manual */}
                    <div className="space-y-2">
                      <label className="text-xs font-black text-slate-400 uppercase tracking-widest">
                        Negara <span className="text-rose-500">*</span>
                      </label>
                      <select {...register('country', { required: watchType === 'international' })}
                        className="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-900 focus:ring-2 focus:ring-toba-green appearance-none">
                        <option value="">-- Pilih Negara --</option>
                        {POPULAR_COUNTRIES.map(c => (
                          <option key={c} value={c}>{c}</option>
                        ))}
                        <option value="__manual__">✏️ Ketik Manual...</option>
                      </select>
                    </div>

                    {watchCountry === '__manual__' && (
                      <input {...register('countryManual')}
                        className="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl font-bold text-slate-900 focus:ring-2 focus:ring-toba-green"
                        placeholder="Nama negara..." />
                    )}

                    {/* Provinsi/Wilayah negara - manual */}
                    <div className="space-y-2">
                      <label className="text-xs font-black text-slate-400 uppercase tracking-widest">
                        Provinsi / Wilayah
                        <span className="text-slate-400 font-normal ml-1">(opsional, manual)</span>
                      </label>
                      <input {...register('region')}
                        className="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl font-medium text-slate-900 focus:ring-2 focus:ring-toba-green"
                        placeholder="Contoh: Phuket, Hokkaido, Tuscany..." />
                    </div>

                    {/* Kota - manual */}
                    <div className="space-y-2">
                      <label className="text-xs font-black text-slate-400 uppercase tracking-widest">
                        Kota / Tempat
                        <span className="text-slate-400 font-normal ml-1">(opsional, manual)</span>
                      </label>
                      <div className="relative">
                        <Navigation className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={15} />
                        <input {...register('placeIntl')}
                          className="w-full pl-11 pr-4 py-3.5 bg-slate-50 border-none rounded-2xl font-medium text-slate-900 focus:ring-2 focus:ring-toba-green"
                          placeholder="Contoh: Kuala Lumpur, Bangkok, Tokyo..." />
                      </div>
                    </div>
                  </>
                )}

                {/* Preview nama */}
                {(() => {
                  const preview = buildName(watch());
                  return preview ? (
                    <div className="bg-toba-green/5 border border-toba-green/20 rounded-2xl p-4">
                      <p className="text-xs font-black text-toba-green uppercase tracking-widest mb-1">Preview Nama Tampilan</p>
                      <p className="font-bold text-slate-900 text-sm">{preview}</p>
                    </div>
                  ) : null;
                })()}

                {/* Deskripsi */}
                <div className="space-y-2">
                  <label className="text-xs font-black text-slate-400 uppercase tracking-widest">Keterangan (opsional)</label>
                  <textarea {...register('description')} rows={2}
                    className="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl font-medium text-slate-700 focus:ring-2 focus:ring-toba-green resize-none"
                    placeholder="Deskripsi singkat destinasi ini..." />
                </div>

                <div className="flex gap-3 pt-2">
                  <button type="button" onClick={() => setIsModalOpen(false)}
                    className="flex-1 py-3.5 rounded-2xl font-bold text-slate-500 hover:bg-slate-50 border border-slate-200 transition-all">
                    Batal
                  </button>
                  <button type="submit"
                    className="flex-1 bg-toba-green text-white py-3.5 rounded-2xl font-bold hover:bg-toba-green/90 transition-all shadow-lg shadow-toba-green/20 flex items-center justify-center gap-2">
                    <Save size={17} />
                    {editingCity ? 'Simpan' : 'Tambah'}
                  </button>
                </div>
              </form>
            </motion.div>
          </div>
        )}
      </AnimatePresence>
    </div>
  );
}
