package com.hostwaypro.bigapp.ui.adapters

import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import com.hostwaypro.bigapp.data.model.WorkRecord
import com.hostwaypro.bigapp.databinding.ItemWorkRecordBinding
import java.text.SimpleDateFormat
import java.util.Locale

class WorkRecordsAdapter : ListAdapter<WorkRecord, WorkRecordsAdapter.ViewHolder>(DiffCallback) {

    class ViewHolder(val binding: ItemWorkRecordBinding) : RecyclerView.ViewHolder(binding.root)

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
        return ViewHolder(ItemWorkRecordBinding.inflate(LayoutInflater.from(parent.context), parent, false))
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        val record = getItem(position)
        val dateFormat = SimpleDateFormat("dd/MM/yyyy", Locale.getDefault())
        
        holder.binding.tvDate.text = record.date?.let { dateFormat.format(it) } ?: "N/A"
        holder.binding.tvDetails.text = "${record.days} Days, ${record.hours} Hours"
        holder.binding.tvAmount.text = "${record.amount} DH"
    }

    object DiffCallback : DiffUtil.ItemCallback<WorkRecord>() {
        override fun areItemsTheSame(oldItem: WorkRecord, newItem: WorkRecord) = oldItem.id == newItem.id
        override fun areContentsTheSame(oldItem: WorkRecord, newItem: WorkRecord) = oldItem == newItem
    }
}
