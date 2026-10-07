package com.hostwaypro.bigapp.ui.adapters

import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import com.hostwaypro.bigapp.data.model.User
import com.hostwaypro.bigapp.databinding.ItemPendingWorkerBinding

class PendingWorkersAdapter(
    private val onApprove: (User) -> Unit,
    private val onReject: (User) -> Unit
) : ListAdapter<User, PendingWorkersAdapter.ViewHolder>(DiffCallback) {

    class ViewHolder(val binding: ItemPendingWorkerBinding) : RecyclerView.ViewHolder(binding.root)

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
        return ViewHolder(ItemPendingWorkerBinding.inflate(LayoutInflater.from(parent.context), parent, false))
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        val user = getItem(position)
        holder.binding.tvName.text = user.name
        holder.binding.tvPhone.text = user.phone
        holder.binding.btnApprove.setOnClickListener { onApprove(user) }
        holder.binding.btnReject.setOnClickListener { onReject(user) }
    }

    object DiffCallback : DiffUtil.ItemCallback<User>() {
        override fun areItemsTheSame(oldItem: User, newItem: User) = oldItem.uid == newItem.uid
        override fun areContentsTheSame(oldItem: User, newItem: User) = oldItem == newItem
    }
}
