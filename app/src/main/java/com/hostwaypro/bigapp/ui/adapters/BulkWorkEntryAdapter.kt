package com.hostwaypro.bigapp.ui.adapters

import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.core.widget.doAfterTextChanged
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import com.hostwaypro.bigapp.data.model.User
import com.hostwaypro.bigapp.data.model.WorkRecord
import com.hostwaypro.bigapp.databinding.ItemBulkEntryBinding
import java.util.Date

class BulkWorkEntryAdapter(
    private val onRecordUpdated: (String, WorkRecord) -> Unit
) : ListAdapter<User, BulkWorkEntryAdapter.ViewHolder>(DiffCallback) {

    private val records = mutableMapOf<String, WorkRecord>()

    fun getRecords(): List<WorkRecord> = records.values.toList()

    class ViewHolder(val binding: ItemBulkEntryBinding) : RecyclerView.ViewHolder(binding.root)

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
        return ViewHolder(ItemBulkEntryBinding.inflate(LayoutInflater.from(parent.context), parent, false))
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        val user = getItem(position)
        val record = records.getOrPut(user.uid) {
            WorkRecord(workerId = user.uid, workerName = user.name, date = Date(), dailyRate = 200.0, hourlyRate = 25.0)
        }

        holder.binding.tvWorkerName.text = user.name
        
        // Remove listeners before setting text to avoid infinite loops
        holder.binding.etDays.setText(if (record.days == 0.0) "" else record.days.toString())
        holder.binding.etHours.setText(if (record.hours == 0.0) "" else record.hours.toString())
        holder.binding.tvAmount.text = "${record.amount} DH"

        holder.binding.etDays.doAfterTextChanged {
            val days = it.toString().toDoubleOrNull() ?: 0.0
            val updated = record.copy(days = days)
            val withAmount = updated.copy(amount = updated.calculateAmount())
            records[user.uid] = withAmount
            holder.binding.tvAmount.text = "${withAmount.amount} DH"
            onRecordUpdated(user.uid, withAmount)
        }

        holder.binding.etHours.doAfterTextChanged {
            val hours = it.toString().toDoubleOrNull() ?: 0.0
            val updated = record.copy(hours = hours)
            val withAmount = updated.copy(amount = updated.calculateAmount())
            records[user.uid] = withAmount
            holder.binding.tvAmount.text = "${withAmount.amount} DH"
            onRecordUpdated(user.uid, withAmount)
        }
    }

    object DiffCallback : DiffUtil.ItemCallback<User>() {
        override fun areItemsTheSame(oldItem: User, newItem: User) = oldItem.uid == newItem.uid
        override fun areContentsTheSame(oldItem: User, newItem: User) = oldItem == newItem
    }
}
